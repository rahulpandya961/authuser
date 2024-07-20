<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::latest()->paginate(10); 
        return view('auth.admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('auth.admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|nullable|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->user_id = auth()->user()->id;
        $product->save();

        return redirect()->route('auth.admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product,$id)
    {
        $product = Product::find($id);
        return view('auth.admin.products.edit', compact('product'));
    }
    public function show(Product $product,$id)
    {
        $product = Product::find($id);
        
        return view('auth.admin.products.show', compact('product'));
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
           'description' => 'required|nullable|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();
        return redirect()->route('auth.admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product,$id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('auth.admin.products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}
