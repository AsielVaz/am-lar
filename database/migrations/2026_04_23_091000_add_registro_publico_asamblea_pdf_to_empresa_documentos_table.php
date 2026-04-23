<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('registro_publico_asamblea_pdf')->nullable()->after('registro_publico_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->dropColumn('registro_publico_asamblea_pdf');
        });
    }
};
