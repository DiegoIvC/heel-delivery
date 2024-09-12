<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table = 'ordenes';
    protected $fillable = [
        'cliente',
        'direccion',
        'estado_entrega',
        'costo_entrega',
        'telefono',
        'repartidor',
        'user_id',
    ];
    public function detalles()
    {
        return $this->hasMany(DetalleOrden::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function repartidor()
    {
        return $this->belongsTo(User::class, 'repartidor');
    }
}
