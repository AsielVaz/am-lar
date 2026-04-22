<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('contrasena_iofacturo')->nullable()->after('logo');
            $table->string('sitio_web')->nullable()->after('contrasena_iofacturo');
            $table->string('telefono')->nullable()->after('sitio_web');
            $table->string('correo')->nullable()->after('telefono');
            $table->date('fin_dominio_web')->nullable()->after('correo');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'contrasena_iofacturo',
                'sitio_web',
                'telefono',
                'correo',
                'fin_dominio_web',
            ]);
        });
    }
};
