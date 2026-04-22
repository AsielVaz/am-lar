<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->dropColumn(['efirma_socios', 'efirma_empresa']);
        });
    }

    public function down(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('efirma_socios')->nullable()->after('fiel_cer');
            $table->string('efirma_empresa')->nullable()->after('efirma_socios');
        });
    }
};
