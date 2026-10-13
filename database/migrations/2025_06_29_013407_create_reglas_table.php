<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Regla;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('reglas', function (Blueprint $table) {
    $table->id();
    $table->decimal('porcentaje', 5, 2);
    $table->integer('umbral_monto');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('reglas');
    }
};
