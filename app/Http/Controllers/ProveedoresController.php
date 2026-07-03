<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProveedoresController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::withCount('batches')
            ->orderBy('name')
            ->get();

        return view('proveedores.index', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'nullable|string|max:100',
            'document_type'   => 'required|in:DNI,RUC,CE,PASSPORT,OTHER',
            'document_number' => 'required|string|max:12',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:9',
        ]);

        Supplier::create($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'nullable|string|max:100',
            'document_type'   => 'required|in:DNI,RUC,CE,PASSPORT,OTHER',
            'document_number' => 'required|string|max:12',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:9',
        ]);

        $supplier->update($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }
}
