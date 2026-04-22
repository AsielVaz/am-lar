<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('acta_constitutiva_pdf')->nullable();
            $table->string('registro_publico_pdf')->nullable();
            $table->string('ine_socios_rfc_pdf')->nullable();
            $table->string('ine_representante_rfc_pdf')->nullable();
            $table->string('efirma_socios')->nullable();
            $table->string('efirma_empresa')->nullable();
            $table->string('comprobante_domicilio_pdf')->nullable();
            $table->timestamps();

            $table->unique('empresa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_documentos');
    }
};
