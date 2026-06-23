<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',

            // 'code' => 'required|string|max:100|unique:products,code',

            'unit' => 'required|in:box,kgs,tray',

            'tray_required' => $request->unit != 'box'
    ? 'required|in:0,1'
    : 'nullable',

            'hsn_no' => 'nullable|string|max:100',

            'gst' => 'nullable|numeric|min:0|max:100',

            'stock' => 'required|integer|min:0',

            'selling_price' => 'nullable|numeric|min:0',

            'status' => 'required|boolean',
        ]);
        $validated['stock'] = $request->stock ?? 0;
        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            // 'code' => 'required|string|max:100|unique:products,code,' . $product->id,

            'unit' => 'required|in:box,kgs,tray',

            'tray_required' => $request->unit != 'box'
    ? 'required|in:0,1'
    : 'nullable',

            'hsn_no' => 'nullable|string|max:100',

            'gst' => 'nullable|numeric|min:0|max:100',

            'stock' => 'nullable|integer|min:0',

            'selling_price' => 'nullable|numeric|min:0',

            'status' => 'required|boolean',
        ]);

        $validated['stock'] = $request->stock ?? 0;

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
