<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
        });

        $demoUserId = DB::table('users')->where('username', 'admin')->value('id');
        if ($demoUserId !== null) {
            DB::table('products')->whereNull('user_id')->update(['user_id' => $demoUserId]);
        }

        Schema::create('product_audits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->string('action', 10);
            $table->string('sku', 30);
            $table->string('product_name', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_audits');
        Schema::table('products', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
