<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProductModel;

class Category extends Controller
{
    public function index($category)
    {
        $productModel = new ProductModel();

        // Get products from database by category
        $products = $productModel->getProductsByCategory($category);

        $data['products'] = $products;
        $data['category'] = ucfirst($category);

        // Load the views
        echo view('templates/header');
        echo view('category_view', $data);
        echo view('templates/footer');
    }
}
