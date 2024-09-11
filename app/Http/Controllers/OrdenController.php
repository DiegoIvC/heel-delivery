<?php

namespace App\Http\Controllers;

use App\Models\DetalleOrden;
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
        // Valida los datos
        $validatedData = $request->validate([
            'cliente' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'costo_entrega' => 'required|numeric|min:0',
            'detalles' => 'required|array',
            'detalles.*.nombre_hamburguesa' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        // Crear la orden con el user_id del usuario autenticado
        $orden = Orden::create([
            'cliente' => $validatedData['cliente'],
            'direccion' => $validatedData['direccion'],
            'estado_entrega' => false,
            'costo_entrega' => $validatedData['costo_entrega'],
            'user_id' => auth()->user()->id,  // Aquí agregas el user_id del usuario autenticado
        ]);

        // Guardar los detalles de la orden
        foreach ($validatedData['detalles'] as $detalle) {
            DetalleOrden::create([
                'orden_id' => $orden->id,
                'nombre_hamburguesa' => $detalle['nombre_hamburguesa'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'precio_total' => $detalle['cantidad'] * $detalle['precio_unitario'],
            ]);
        }

        return redirect()->back()->with('flash.success', 'Orden creada con éxito');
    }
}
