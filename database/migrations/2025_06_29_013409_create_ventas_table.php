<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Venta;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('ventas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendedor_id')
          ->constrained('vendedores')
          ->onDelete('cascade');
    $table->date('fecha_venta');
    $table->integer('monto');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
