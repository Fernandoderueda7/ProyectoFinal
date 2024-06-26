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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table -> foreignId('user_id') -> constrained() -> onDelete('cascade');
            $table->string('nombre_producto');
            $table->string('marca');
            $table->string('descripcion');
            $table->float('precio', $precision = 8, $scale = 2);
            $table->string('categoria');
            $table->string('deporte');
            $table->string('estado');
            $table->string('tienda');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
