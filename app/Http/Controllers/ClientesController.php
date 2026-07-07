<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientesController extends Controller
{
    public function index(): View
    {
        $clients = Client::orderBy('name')->get();

        return view('logistica.clientes', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'document_type'   => 'required|in:DNI,RUC,CE,PASSPORT,OTHER',
            'document_number' => [
                'required',
                'string',
                'max:12',
                Rule::unique('clients')->where(function ($query) use ($request) {
                    return $query->where('document_type', $request->document_type);
                }),
            ],
            'name'            => 'required|string|max:100',
            'lastname'        => 'nullable|string|max:100',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:9',
        ], [
            'document_number.unique' => 'Este número de documento ya está registrado para este tipo.',
        ]);

        Client::create([
            'document_type'   => $request->document_type,
            'document_number' => $request->document_number,
            'name'            => $request->name,
            'lastname'        => $request->lastname ?? '',
            'email'           => $request->email,
            'phone'           => $request->phone,
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        if ($client->document_number === '00000000') {
            return redirect()->route('clientes.index')
                ->withErrors(['error' => 'No está permitido modificar el cliente general de venta libre.']);
        }

        $request->validate([
            'document_type'   => 'required|in:DNI,RUC,CE,PASSPORT,OTHER',
            'document_number' => [
                'required',
                'string',
                'max:12',
                Rule::unique('clients')->where(function ($query) use ($request) {
                    return $query->where('document_type', $request->document_type);
                })->ignore($client->id),
            ],
            'name'            => 'required|string|max:100',
            'lastname'        => 'nullable|string|max:100',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:9',
        ], [
            'document_number.unique' => 'Este número de documento ya está registrado para este tipo.',
        ]);

        $client->update([
            'document_type'   => $request->document_type,
            'document_number' => $request->document_number,
            'name'            => $request->name,
            'lastname'        => $request->lastname ?? '',
            'email'           => $request->email,
            'phone'           => $request->phone,
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }
}
