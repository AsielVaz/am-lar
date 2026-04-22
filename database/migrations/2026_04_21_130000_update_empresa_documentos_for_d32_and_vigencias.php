<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->dropColumn(['ine_socios_rfc_pdf', 'ine_representante_rfc_pdf']);
        });

        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('d32_pdf')->nullable()->after('registro_publico_pdf');
            $table->date('d32_vigencia_inicio')->nullable()->after('d32_pdf');
            $table->date('comprobante_domicilio_vigencia_inicio')->nullable()->after('comprobante_domicilio_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('ine_socios_rfc_pdf')->nullable()->after('registro_publico_pdf');
            $table->string('ine_representante_rfc_pdf')->nullable()->after('ine_socios_rfc_pdf');
            $table->dropColumn(['d32_pdf', 'd32_vigencia_inicio', 'comprobante_domicilio_vigencia_inicio']);
        });
    }
};
