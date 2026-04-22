<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->string('estatus', 20)->default('activa')->after('rfc');
            $table->string('foto_usuario')->nullable()->after('contrasena');
        });

        DB::table('socios')->whereNull('estatus')->update(['estatus' => 'activa']);
    }

    public function down(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->dropColumn(['estatus', 'foto_usuario']);
        });
    }
};
