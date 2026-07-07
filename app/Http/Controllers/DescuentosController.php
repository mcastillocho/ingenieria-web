<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DescuentosController extends Controller
{
    public function index(): View
    {
        $discounts = Discount::orderBy('created_at', 'desc')->get();

        return view('logistica.descuentos', compact('discounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'            => 'required|string|max:50|unique:discounts,code',
            'type_use'        => 'required|in:AUTOMATIC,MANUAL',
            'type_discount'   => 'required|in:AMOUNT,PERCENTAGE',
            'amount'          => 'required|numeric|min:0.01',
            'minimum_amount'  => 'required|numeric|min:0',
            'maximum_amount'  => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'use_limit'       => 'required|integer|min:0',
            'type_limit'      => 'required|in:FOR_PRODUCT,FOR_SALE,UNLIMITED',
        ], [
            'code.unique' => 'El código de descuento ya existe.',
        ]);

        Discount::create($validated);

        return redirect()->route('descuentos.index')
            ->with('success', 'Cupón de descuento creado correctamente.');
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validate([
            'code'            => 'required|string|max:50|unique:discounts,code,' . $discount->id,
            'type_use'        => 'required|in:AUTOMATIC,MANUAL',
            'type_discount'   => 'required|in:AMOUNT,PERCENTAGE',
            'amount'          => 'required|numeric|min:0.01',
            'minimum_amount'  => 'required|numeric|min:0',
            'maximum_amount'  => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'use_limit'       => 'required|integer|min:0',
            'type_limit'      => 'required|in:FOR_PRODUCT,FOR_SALE,UNLIMITED',
        ], [
            'code.unique' => 'El código de descuento ya existe.',
        ]);

        $discount->update($validated);

        return redirect()->route('descuentos.index')
            ->with('success', 'Cupón de descuento actualizado correctamente.');
    }
}
