<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('sello_sat_key_contrasena')->nullable()->after('sello_sat_key');
            $table->string('fiel_key_contrasena')->nullable()->after('fiel_key');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->dropColumn(['sello_sat_key_contrasena', 'fiel_key_contrasena']);
        });
    }
};
