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

        // SORT FIRST (newest first)
        usort($products, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $products = $this->getProducts();

        $newProduct = [
            'name' => $request->name,
            'quantity' => (int) $request->quantity,
            'price' => (float) $request->price,
            'total' => (int)$request->quantity * (float)$request->price,
            'created_at' => now()->toDateTimeString()
        ];

        // EDIT MODE
        if ($request->has('index') && isset($products[$request->index])) {
            $products[$request->index] = $newProduct;
        } else {
            $products[] = $newProduct;
        }

        $this->saveProducts($products);

        return response()->json(['success' => true]);
    }

    private function getProducts()
    {
        $path = storage_path('app/' . $this->file);

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    private function saveProducts($products)
    {
        $path = storage_path('app/' . $this->file);

        file_put_contents($path, json_encode($products, JSON_PRETTY_PRINT));
    }

    public function delete(Request $request)
{
    $products = $this->getProducts();

    $index = $request->index;

    if (isset($products[$index])) {
        array_splice($products, $index, 1);
        $this->saveProducts($products);
    }

    return response()->json(['success' => true]);
}
}