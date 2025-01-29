<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select([
            'id','name','description','image'
        ])->get()->map(function($item){
            $item->image = url("images/".$item->image);
            return $item;
        });
        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) return response()->json('Category not found', 404);
        $data = [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'image' => url('images/' . $category->image),
            'products' => $category->products->map(function($product){
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => url('images/' . $product->main_image),
                ];
            })
        ];
        return response()->json($data);
    }
}
