<?php

use App\Enums\RolPersonalTi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_ti', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('rol')->default(RolPersonalTi::Becario->value);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_ti');
    }
};
