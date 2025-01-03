<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'required|string'
        ]);
        $category = Category::create($validate);
        return response()->json($category);

    }

    /**
     * Display the specified resource.
     */
    public function show($category_id)
    {
        $category = Category::find($category_id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category_id)
    {
        $validate = $request->validate([
            'name'=>'required|string',
            'description'=>'required|string'
        ]);
        $category = Category::find($category_id)->update($validate);
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category_id)
    {
        $category = Category::find($category_id)->delete();
        return response()->json(['message'=>'The category deleted']);
    }
}
