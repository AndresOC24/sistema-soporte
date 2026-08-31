<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soporte_problema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soporte_id')->constrained('soportes')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->foreignId('problema_id')->constrained('problemas')->restrictOnDelete();

            $table->unique(['soporte_id', 'problema_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soporte_problema');
    }
};
