<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->string('asamblea_pdf')->nullable()->after('acta_constitutiva_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_documentos', function (Blueprint $table) {
            $table->dropColumn('asamblea_pdf');
        });
    }
};
