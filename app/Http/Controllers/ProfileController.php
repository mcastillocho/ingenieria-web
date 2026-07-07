<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $workerId = session('worker_id');
        $worker = Worker::findOrFail($workerId);
        $credential = Credential::where('worker_id', $workerId)->firstOrFail();

        return view('perfil', compact('worker', 'credential'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $workerId = session('worker_id');
        $credential = Credential::where('worker_id', $workerId)->firstOrFail();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'new_password.required'     => 'La nueva contraseña es obligatoria.',
            'new_password.min'          => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed'    => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        if (!password_verify($request->current_password, $credential->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $credential->password = password_hash($request->new_password, PASSWORD_BCRYPT);
        $credential->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
