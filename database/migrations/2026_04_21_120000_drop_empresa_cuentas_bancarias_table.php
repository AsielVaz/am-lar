<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('empresa_cuentas_bancarias');
    }

    public function down(): void
    {
        Schema::create('empresa_cuentas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->string('banco');
            $table->string('numero_cuenta');
            $table->string('usuario');
            $table->string('contrasena');
            $table->string('caratula')->nullable();
            $table->string('estado_cuenta')->nullable();
            $table->timestamps();
        });
    }
};
