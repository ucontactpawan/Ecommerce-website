<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

class CartController extends BaseController
{
    public function addToCart()
    {
        $cartModel = new CartModel();
        $productModel = new ProductModel();
        $productId = $this->request->getPost('product_id');
        $userId = session()->get('id');
        $sessionId = session()->get('session_id') ?: session_id();
        $isAjax = $this->request->isAJAX();

        // Store session_id in session for consistency
        if (!session()->get('session_id')) {
            session()->set('session_id', $sessionId);
        }

        // Get product details
        $product = $productModel->find($productId);
        if (!$product) {
            if ($isAjax) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Product not found.'
                ]);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        try {
            // Prepare cart data
            $cartData = [
                'product_id' => $productId,
                'quantity' => 1,
                'price' => is_array($product) ? $product['price'] : $product->price
            ];

            // Add user_id or session_id based on login status
            if ($userId) {
                $cartData['user_id'] = $userId;
            } else {
                $cartData['session_id'] = $sessionId;
            }

            // Use the addToCart method from CartModel
            $result = $cartModel->addToCart($cartData);

            if ($result) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Item added to cart successfully'
                    ]);
                }
                return redirect()->back()->with('success', 'Item added to cart successfully');
            } else {
                throw new \Exception('Failed to add item to cart');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error adding to cart: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add item to cart'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to add item to cart');
        }
    }

    public function viewCart()
    {
        try {
            $cartModel = new CartModel();
            $userId = session()->get('id');
            $sessionId = session()->get('session_id') ?: session_id();

            // Debug info
            log_message('debug', 'CartController::viewCart - userId: ' . ($userId ?: 'null') . ', sessionId: ' . $sessionId);

            // Get cart items using the new method
            $cartItems = $cartModel->getCartItems($userId, $sessionId);

            // Debug info
            log_message('debug', 'CartController::viewCart - cartItems count: ' . count($cartItems));

            // Calculate total
            $total = $cartModel->getCartTotal($userId, $sessionId);

            return view('cart/view', [
                'cartItems' => $cartItems,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            log_message('error', 'CartController::viewCart error: ' . $e->getMessage());
            return view('cart/view', [
                'cartItems' => [],
                'total' => 0
            ]);
        }
    }

    public function count()
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');
        $sessionId = session()->get('session_id') ?: session_id();

        // Store session_id in session for consistency
        if (!session()->get('session_id')) {
            session()->set('session_id', $sessionId);
        }

        $count = $cartModel->getCartCount($userId, $sessionId);
        return $this->response->setJSON(['count' => $count]);
    }

    public function removeFromCart($id)
    {
        try {
            $cartModel = new CartModel();
            $userId = session()->get('id');
            $sessionId = session()->get('session_id') ?: session_id();

            // Build query conditions for either logged-in user or guest session
            $conditions = ['id' => $id];
            if ($userId) {
                $conditions['user_id'] = $userId;
            } else {
                $conditions['session_id'] = $sessionId;
            }

            $cartItem = $cartModel->where($conditions)->first();

            if (!$cartItem) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Cart item not found']);
            }

            if ($cartModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Product removed successfully'
                ]);
            }

            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'Failed to remove item']);
        } catch (\Exception $e) {
            log_message('error', 'Error removing cart item: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'An error occurred']);
        }
    }

    public function updateQuantity($id, $quantity)
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');
        $sessionId = session()->get('session_id') ?: session_id();

        // Build query conditions for either logged-in user or guest session
        $conditions = ['id' => $id];
        if ($userId) {
            $conditions['user_id'] = $userId;
        } else {
            $conditions['session_id'] = $sessionId;
        }

        $cartItem = $cartModel->where($conditions)->first();

        if (!$cartItem) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Item not found in cart']);
        }

        if ((int)$quantity < 1) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid quantity']);
        }

        $cartModel->update($id, ['quantity' => $quantity]);
        return $this->response->setJSON(['success' => true]);
    }

    public function debug()
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');
        $sessionId = session()->get('session_id') ?: session_id();

        $debugInfo = [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'cart_count' => $cartModel->getCartCount($userId, $sessionId),
            'session_data' => session()->get(),
            'post_data' => $this->request->getPost()
        ];

        return $this->response->setJSON($debugInfo);
    }
}
