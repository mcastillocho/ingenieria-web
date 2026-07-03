<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductosController extends Controller
{
    public function index(): View
    {
        $products = Product::with('productCategory')
            ->withSum('batches', 'current_stock')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::orderBy('name')->pluck('name', 'id');

        return view('logistica.productos', compact('products', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('new_category_name')) {
            $request->validate(['new_category_name' => 'required|string|max:100']);
            $category = ProductCategory::firstOrCreate(
                ['name' => $request->new_category_name]
            );
            $categoryId = $category->id;
        } else {
            $request->validate(['product_category_id' => 'required|exists:product_categories,id']);
            $categoryId = $request->product_category_id;
        }

        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'sale_price'  => 'required|numeric|min:0.01',
        ]);

        Product::create([
            'product_category_id' => $categoryId,
            'name'                => $request->name,
            'description'         => $request->description,
            'sale_price'          => $request->sale_price,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function update(Request $request, Product $producto): RedirectResponse
    {
        if ($request->filled('new_category_name')) {
            $request->validate(['new_category_name' => 'required|string|max:100']);
            $category = ProductCategory::firstOrCreate(
                ['name' => $request->new_category_name]
            );
            $categoryId = $category->id;
        } else {
            $request->validate(['product_category_id' => 'required|exists:product_categories,id']);
            $categoryId = $request->product_category_id;
        }

        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'sale_price'  => 'required|numeric|min:0.01',
        ]);

        $producto->update([
            'product_category_id' => $categoryId,
            'name'                => $request->name,
            'description'         => $request->description,
            'sale_price'          => $request->sale_price,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }
}
