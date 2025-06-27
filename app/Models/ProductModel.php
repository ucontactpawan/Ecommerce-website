<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'image', 'category', 'stock_quantity', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getProductsByCategory($category)
    {
        return $this->where('category', $category)
            ->where('is_active', true)
            ->findAll();
    }

    public function getActiveProducts($limit = null)
    {
        $builder = $this->where('is_active', true);
        if ($limit) {
            $builder->limit($limit);
        }
        return $builder->findAll();
    }

    public function getCategoriesWithProducts()
    {
        return $this->select('category, MIN(price) as min_price, COUNT(*) as product_count')
            ->where('is_active', true)
            ->groupBy('category')
            ->findAll();
    }
}
