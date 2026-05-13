<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $file = 'products.json';

    public function index()
    {
        return view('welcome');
    }

    public function list()
    {
        $products = $this->getProducts();
        return response()->json($products);

        usort($products, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
    }

    public function store(Request $request)
    {
        $products = $this->getProducts();

        $request->validate([
        'name' => 'required',
        'quantity' => 'required|numeric',
        'price' => 'required|numeric'
    ]);

        $newProduct = [
            'name' => $request->name,
            'quantity' => (int)$request->quantity,
            'price' => (float)$request->price,
            'total' => (int)$request->quantity * (float)$request->price,
            'created_at' => now()->toDateTimeString()
        ];

        $products[] = $newProduct;

        $this->saveProducts($products);

        return response()->json(['success' => true]);
    }

    private function getProducts()
    {
        $path = storage_path('app/' . $this->file);

        if (!file_exists($path)) {
            return [];
        }

        $data = file_get_contents($path);
        return json_decode($data, true) ?? [];
    }

    private function saveProducts($products)
    {
        $path = storage_path('app/' . $this->file);

        file_put_contents($path, json_encode($products, JSON_PRETTY_PRINT));
    }
}