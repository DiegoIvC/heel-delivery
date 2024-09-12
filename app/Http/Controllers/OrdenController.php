<?php

namespace App\Http\Controllers;

use App\Models\DetalleOrden;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Orden;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    public function index()
    {
        // Obtener el monto total de los detalles de cada orden
        $subconsulta = DB::table('detalles_orden')
            ->select('orden_id', DB::raw('SUM(precio_total) as monto_total'))
            ->groupBy('orden_id');

// Obtener todas las órdenes junto con sus detalles y el nombre del repartidor
        $ordenes = DB::table('ordenes')
            ->leftJoinSub($subconsulta, 'detalles_totales', function ($join) {
                $join->on('ordenes.id', '=', 'detalles_totales.orden_id');
            })
            ->leftJoin('users as repartidores', 'ordenes.repartidor', '=', 'repartidores.id')
            ->select('ordenes.*', 'repartidores.name as repartidor_nombre', 'detalles_totales.monto_total')
            ->get();

// Transformar los datos para enviar a Vue
        $ordenesData = $ordenes->map(function ($orden) {
            return [
                'id' => $orden->id,
                'cliente' => $orden->cliente,
                'direccion' => $orden->direccion,
                'telefono' => $orden->telefono,
                'costo_entrega' => $orden->costo_entrega,
                'monto_total' => $orden->monto_total + $orden->costo_entrega,
                'estado_entrega' => $orden->estado_entrega ? 'Entregado' : 'No entregado',
                'repartidor' => $orden->repartidor_nombre ?? 'Sin repartidor',
            ];
        });

        return Inertia::render('Index', [
            'ordenes' => $ordenesData
        ]);
    }



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
        try {
            // Valida los datos
            $validatedData = $request->validate([
                'cliente' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'telefono' => 'nullable',
                'costo_entrega' => 'required|numeric|min:0',
                'vendedor' => 'nullable|string',
                'detalles' => 'required|array',
                'detalles.*.nombre_hamburguesa' => 'required|string|max:255',
                'detalles.*.cantidad' => 'required|integer|min:1',
                'detalles.*.precio_unitario' => 'required|numeric|min:0',
            ]);

            // Crear la orden con el user_id del usuario autenticado
            $orden = Orden::create([
                'cliente' => $validatedData['cliente'],
                'direccion' => $validatedData['direccion'],
                'telefono' => $validatedData['telefono'],
                'vendedor'=> $validatedData['vendedor'],
                'estado_entrega' => false,
                'costo_entrega' => $validatedData['costo_entrega'],
                'user_id' => auth()->user()->id,
                'repartidor' => auth()->user()->id,
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

            // Retornar mensaje de éxito
            return response()->json([
                'success' => true,
                'message' => 'Orden creada con éxito',
                'orden_id' => $orden->id  // Puedes incluir más detalles si lo deseas
            ], 200);

        } catch (\Exception $e) {
            // Retornar mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear la orden',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        $orden = Orden::with('detalles', 'repartidor')->findOrFail($id);

        return Inertia::render('Show', [
            'orden' => $orden,
            'detalles' => $orden->detalles,
            'repartidor' => $orden->repartidor ? $orden->repartidor->name : 'Sin repartidor',
        ]);
    }

}
