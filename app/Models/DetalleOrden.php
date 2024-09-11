<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;
    protected $table = 'detalles_orden';
    protected $fillable = [
        'orden_id',            // Agrega este campo
        'nombre_hamburguesa',
        'caracteristicas',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'refresco',
    ];
    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
