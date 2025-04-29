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
        Schema::create('listaDemandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demandas_id')->constrained()->onDelete('cascade');
            $table->foreignId('fornecedors_id')->constrained()->onDelete('cascade');
            $table->integer('ultima_posicao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
