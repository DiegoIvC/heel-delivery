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

        // Obtener todas las órdenes junto con sus detalles, el nombre del repartidor y la zona
        $ordenes = DB::table('ordenes')
            ->leftJoinSub($subconsulta, 'detalles_totales', function ($join) {
                $join->on('ordenes.id', '=', 'detalles_totales.orden_id');
            })
            ->leftJoin('users as repartidores', 'ordenes.repartidor', '=', 'repartidores.id')
            ->where('ordenes.estado', '=', 0) // Filtro para estado 0
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
                'zona' => $orden->zona // Asegúrate de que esta línea esté incluida
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
    {// Obtener la orden y sus detalles
        $orden = DB::table('ordenes')
            ->leftJoin('users as repartidores', 'ordenes.repartidor', '=', 'repartidores.id')
            ->leftJoin('detalles_orden', 'ordenes.id', '=', 'detalles_orden.orden_id')
            ->where('ordenes.id', $id)
            ->select(
                'ordenes.id',
                'ordenes.cliente',
                'ordenes.direccion',
                'ordenes.telefono',
                'ordenes.costo_entrega',
                'ordenes.estado_entrega',
                DB::raw('SUM(detalles_orden.precio_total) as monto_total'),
                'repartidores.name as repartidor_nombre',
                'ordenes.zona'
            )
            ->groupBy(
                'ordenes.id',
                'ordenes.cliente',
                'ordenes.direccion',
                'ordenes.telefono',
                'ordenes.costo_entrega',
                'ordenes.estado_entrega',
                'repartidores.name',
                'ordenes.zona'
            )
            ->first();

// Obtener los detalles de la orden
            $detalles = DB::table('detalles_orden')
                ->where('orden_id', $id)
                ->get();

// Verificar si se encontró la orden
            if (!$orden) {
                abort(404, 'Orden no encontrada');
            }

// Retornar la orden y detalles a la vista
            return Inertia::render('Show', [
                'orden' => $orden, // Datos de la orden
                'detalles' => $detalles, // Datos de los detalles
            ]);


        }

    public function edit($id)
    {
        {// Obtener la orden y sus detalles
            $orden = DB::table('ordenes')
                ->leftJoin('users as repartidores', 'ordenes.repartidor', '=', 'repartidores.id')
                ->leftJoin('detalles_orden', 'ordenes.id', '=', 'detalles_orden.orden_id')
                ->where('ordenes.id', $id)
                ->select(
                    'ordenes.id',
                    'ordenes.cliente',
                    'ordenes.direccion',
                    'ordenes.telefono',
                    'ordenes.costo_entrega',
                    'ordenes.estado_entrega',
                    DB::raw('SUM(detalles_orden.precio_total) + ordenes.costo_entrega as monto_total'),
                    'repartidores.name as repartidor_nombre',
                    'ordenes.zona'
                )
                ->groupBy(
                    'ordenes.id',
                    'ordenes.cliente',
                    'ordenes.direccion',
                    'ordenes.telefono',
                    'ordenes.costo_entrega',
                    'ordenes.estado_entrega',
                    'repartidores.name',
                    'ordenes.zona'
                )
                ->orderBy('ordenes.zona', 'DESC')
                ->first();

// Obtener los detalles de la orden
            $detalles = DB::table('detalles_orden')
                ->where('orden_id', $id)
                ->get();

// Verificar si se encontró la orden
            if (!$orden) {
                abort(404, 'Orden no encontrada');
            }

// Retornar la orden y detalles a la vista
            return Inertia::render('Orden/Edit', [
                'orden' => $orden, // Datos de la orden
                'detalles' => $detalles, // Datos de los detalles
            ]);
    }
    }

    public function update(Request $request, $id)
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

            // Encuentra la orden a actualizar
            $orden = Orden::findOrFail($id);

            // Actualiza la orden
            $orden->update([
                'cliente' => $validatedData['cliente'],
                'direccion' => $validatedData['direccion'],
                'telefono' => $validatedData['telefono'],
                'vendedor'=> $validatedData['vendedor'],
                'costo_entrega' => $validatedData['costo_entrega'],
            ]);

            // Elimina los detalles actuales
            $orden->detalles()->delete();

            // Guarda los nuevos detalles de la orden
            foreach ($validatedData['detalles'] as $detalle) {
                DetalleOrden::create([
                    'orden_id' => $orden->id,
                    'nombre_hamburguesa' => $detalle['nombre_hamburguesa'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'precio_total' => $detalle['cantidad'] * $detalle['precio_unitario'],
                ]);
            }

            // Retorna mensaje de éxito
            return response()->json([
                'success' => true,
                'message' => 'Orden actualizada con éxito',
            ], 200);

        } catch (\Exception $e) {
            // Retorna mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la orden',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateZona(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);
        $orden->zona = $request->input('zona');
        $orden->save();

        return redirect()->back()->with('success', 'Zona actualizada con éxito');
    }
    public function terminar($id)
    {
        // Encontrar la orden por ID
        $orden = DB::table('ordenes')->where('id', $id)->first();

        if ($orden) {
            // Actualizar el estado de la orden a 1
            DB::table('ordenes')->where('id', $id)->update(['estado' => 1]);

            return redirect()->route('ordenes.index');
        }

        return response()->json(['message' => 'Orden no encontrada.'], 404);
    }

    public function realizados(){
        return response('SI JALA');
    }
    public function enviosRealizados(){
        return response('SI JALA');
    }
    public function enviosPendientes(){
        return response('SI JALA');
    }
}
