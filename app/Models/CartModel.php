<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'session_id', 'product_id', 'quantity', 'price'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCartItems($userId = null, $sessionId = null)
    {
        // First get cart items
        $builder = $this->where('1=1'); // Start with a condition

        if ($userId) {
            $builder->where('user_id', $userId);
        } elseif ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $cartItems = $builder->findAll();

        // Then add product details to each cart item
        if (!empty($cartItems)) {
            $productModel = new \App\Models\ProductModel();

            foreach ($cartItems as &$item) {
                $product = $productModel->find($item['product_id']);
                if ($product) {
                    $item['name'] = $product['name'];
                    $item['image'] = $product['image'];
                    $item['stock_quantity'] = $product['stock_quantity'];
                } else {
                    // Product not found or inactive
                    $item['name'] = 'Product not found';
                    $item['image'] = 'default.jpg';
                    $item['stock_quantity'] = 0;
                }
            }
        }

        return $cartItems;
    }

    public function addToCart($data)
    {
        // Check if item already exists in cart
        $builder = $this->where('product_id', $data['product_id']);

        if (isset($data['user_id'])) {
            $builder->where('user_id', $data['user_id']);
        } else {
            $builder->where('session_id', $data['session_id']);
        }

        $existing = $builder->first();

        if ($existing) {
            // Update quantity if item exists
            return $this->update($existing['id'], [
                'quantity' => $existing['quantity'] + $data['quantity'],
                'price' => $data['price']
            ]);
        } else {
            // Insert new item
            return $this->insert($data);
        }
    }

    public function updateQuantity($cartId, $quantity)
    {
        return $this->update($cartId, ['quantity' => $quantity]);
    }

    public function removeFromCart($cartId)
    {
        return $this->delete($cartId);
    }

    public function clearCart($userId = null, $sessionId = null)
    {
        $builder = $this;

        if ($userId) {
            $builder->where('user_id', $userId);
        } elseif ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        return $builder->delete();
    }
    public function getCartTotal($userId = null, $sessionId = null)
    {
        $builder = $this->select('SUM(quantity * price) as total');

        if ($userId) {
            $builder->where('user_id', $userId);
        } elseif ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $result = $builder->first();
        return $result['total'] ?? 0;
    }

    public function getCartCount($userId = null, $sessionId = null)
    {
        $builder = $this->selectSum('quantity', 'total_items');

        if ($userId) {
            $builder->where('user_id', $userId);
        } elseif ($sessionId) {
            $builder->where('session_id', $sessionId);
        }

        $result = $builder->first();
        return $result['total_items'] ?? 0;
    }
}
