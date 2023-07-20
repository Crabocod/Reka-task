<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index(){
        $products = Product::where('in_stock', '>', 1)->orderBy('created_at', 'desc')->get();
        $products = $products->toArray();

        $products_html = '';
        foreach ($products as $product)
            $products_html .= view('templates.products', $product);

        return view('catalog', ['products' => $products_html]);
    }
}
