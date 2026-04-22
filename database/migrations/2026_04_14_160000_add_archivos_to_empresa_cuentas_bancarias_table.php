<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_cuentas_bancarias', function (Blueprint $table) {
            $table->string('caratula')->nullable()->after('contrasena');
            $table->string('estado_cuenta')->nullable()->after('caratula');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_cuentas_bancarias', function (Blueprint $table) {
            $table->dropColumn(['caratula', 'estado_cuenta']);
        });
    }
};
