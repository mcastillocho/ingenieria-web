<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Credential;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only(['username', 'password']);

        if (empty($data['username']) || empty($data['password'])) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'username and password required'], 422);
            }

            return redirect()->back()->with('error', 'Usuario y contraseña son requeridos');
        }

        $credential = Credential::where('username', $data['username'])->first();

        if (!$credential || !password_verify($data['password'], $credential->password)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            return redirect()->back()->with('error', 'Contraseña incorrecta');
        }

        $request->session()->put('worker_id', $credential->worker_id);
        $request->session()->put('role', $credential->role);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'worker' => $credential->worker, 'role' => $credential->role]);
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect('/login');
    }

    public function me(Request $request)
    {
        $workerId = $request->session()->get('worker_id');

        if (!$workerId) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $worker = \App\Models\Worker::find($workerId);

        if (!$worker) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['worker' => $worker]);
    }
}
