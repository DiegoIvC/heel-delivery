<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Orden;

class OrdenController extends Controller
{
    public function create()
    {
        return Inertia::render('Create', [
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'estado_entrega' => 'required|boolean',
            'costo_entrega' => 'required|numeric',
        ]);

        $orden = Orden::create([
            'cliente' => $validated['cliente'],
            'direccion' => $validated['direccion'],
            'estado_entrega' => $validated['estado_entrega'],
            'costo_entrega' => $validated['costo_entrega'],
            'user_id' => auth()->id(), // Asocia la orden con el usuario autenticado
        ]);

        return redirect()->route('dashboard')->with('success', 'Orden creada con éxito.');
    }
}
