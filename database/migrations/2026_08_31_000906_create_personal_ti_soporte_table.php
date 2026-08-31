<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_ti_soporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soporte_id')->constrained('soportes')->cascadeOnDelete();
            $table->foreignId('personal_ti_id')->constrained('personal_ti')->restrictOnDelete();

            $table->unique(['soporte_id', 'personal_ti_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_ti_soporte');
    }
};
