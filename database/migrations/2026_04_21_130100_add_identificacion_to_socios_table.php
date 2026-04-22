<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->string('ine_pdf')->nullable()->after('contrasena');
            $table->string('csf_pdf')->nullable()->after('ine_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->dropColumn(['ine_pdf', 'csf_pdf']);
        });
    }
};
