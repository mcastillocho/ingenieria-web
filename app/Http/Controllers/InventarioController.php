<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function index(): View
    {
        $batches = Batch::with(['product.category', 'supplier'])
            ->orderByDesc('created_at')
            ->get();

        $products   = Product::orderBy('name')->pluck('name', 'id');
        $suppliers  = Supplier::orderBy('name')->pluck('name', 'id');
        $categories = ProductCategory::orderBy('name')->pluck('name', 'id');

        return view('inventario.index', compact('batches', 'products', 'suppliers', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        // ── ¿Se está creando un producto nuevo? ────────────────────────────
        if ($request->filled('new_product_name')) {
            $request->validate([
                'new_product_name'        => 'required|string|max:100',
                'new_product_sale_price'  => 'required|numeric|min:0.01',
                'new_product_description' => 'nullable|string|max:1000',
            ]);

            // ¿Categoría nueva o existente?
            if ($request->filled('new_category_name')) {
                $request->validate(['new_category_name' => 'required|string|max:100']);
                $category = ProductCategory::firstOrCreate(
                    ['name' => $request->new_category_name]
                );
                $categoryId = $category->id;
            } else {
                $request->validate(['new_product_category_id' => 'required|exists:product_categories,id']);
                $categoryId = $request->new_product_category_id;
            }

            $product = Product::create([
                'product_category_id' => $categoryId,
                'name'                => $request->new_product_name,
                'description'         => $request->new_product_description,
                'sale_price'          => $request->new_product_sale_price,
            ]);

            $productId   = $product->id;
            $productName = $product->name;
        } else {
            $request->validate(['product_id' => 'required|exists:products,id']);
            $productId   = $request->product_id;
            $productName = null;
        }

        // ── ¿Se está creando un proveedor nuevo? ───────────────────────────
        if ($request->filled('new_supplier_name')) {
            $request->validate([
                'new_supplier_name'            => 'required|string|max:100',
                'new_supplier_document_type'   => 'required|in:DNI,RUC,CE,PASSPORT,OTHER',
                'new_supplier_document_number' => 'required|string|max:12',
                'new_supplier_email'           => 'nullable|email|max:255',
                'new_supplier_phone'           => 'nullable|string|max:9',
            ]);

            $supplier = Supplier::create([
                'name'            => $request->new_supplier_name,
                'document_type'   => $request->new_supplier_document_type,
                'document_number' => $request->new_supplier_document_number,
                'email'           => $request->new_supplier_email,
                'phone'           => $request->new_supplier_phone,
            ]);

            $supplierId   = $supplier->id;
            $supplierName = $supplier->name;
        } else {
            $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
            $supplierId   = $request->supplier_id;
            $supplierName = null;
        }

        // ── Campos comunes del lote ────────────────────────────────────────
        $request->validate([
            'initial_stock'  => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0.01',
        ]);

        Batch::create([
            'product_id'     => $productId,
            'supplier_id'    => $supplierId,
            'initial_stock'  => $request->initial_stock,
            'current_stock'  => $request->initial_stock,
            'purchase_price' => $request->purchase_price,
        ]);

        $msg = 'Lote creado correctamente.';
        if ($productName && $supplierName) {
            $msg = "Producto \"{$productName}\", proveedor \"{$supplierName}\" y lote creados.";
        } elseif ($productName) {
            $msg = "Producto \"{$productName}\" y lote creados correctamente.";
        } elseif ($supplierName) {
            $msg = "Proveedor \"{$supplierName}\" y lote creados correctamente.";
        }

        return redirect()->route('inventario.index')->with('success', $msg);
    }

    public function update(Request $request, Batch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'initial_stock'  => 'required|integer|min:1',
            'current_stock'  => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0.01',
        ]);

        $batch->update($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Lote actualizado correctamente.');
    }
}
