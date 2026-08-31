<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soportes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');

            /**
             * La duracion es columna generada: no se captura ni se puede desincronizar
             * de las horas, y el dashboard la puede sumar directamente en SQL.
             */
            $table->integer('duracion_minutos')
                ->storedAs('(TIME_TO_SEC(hora_fin) - TIME_TO_SEC(hora_inicio)) DIV 60');

            $table->foreignId('solicitante_id')->constrained('solicitantes')->restrictOnDelete();
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->restrictOnDelete();
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soportes');
    }
};
