<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('direccion');
            $table->integer('estado_entrega')->default(0);
            $table->integer('estado')->default(0);
            $table->decimal('costo_entrega', 10, 2);
            $table->foreignId('user_id')->constrained('users');
            $table->string('telefono')->nullable(); // Columna teléfono
            $table->string('vendedor')->nullable(); // Columna teléfono
            $table->string('zona')->default('VERDE');
            $table->foreignId('repartidor')->nullable()->constrained('users')->onDelete('set null'); // Llave foránea a users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
