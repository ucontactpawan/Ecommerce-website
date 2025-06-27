<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        // Get featured products (first 6 products)
        $data['products'] = $productModel->getActiveProducts(6);

        // Get electronics for the electronics section
        $data['electronics'] = $productModel->getProductsByCategory('electronics');

        return view('home', $data);
    }
}
