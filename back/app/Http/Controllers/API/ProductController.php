<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $data = [];
        foreach ($products as $product) {
            $item = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => url('images/' . $product->main_image),
                'category' => $product->category->name,
            ];
            $data[] = $item;
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json('Product not found', 404);
        $data = [
            'id' => $product->id,
            'name' =>  $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'images' => collect(json_decode($product->images))->map(function ($item) {
                return url('images/' . $item);
            }),
            'category' => $product->category->name,
        ];
        return response()->json($data);
    }
}
