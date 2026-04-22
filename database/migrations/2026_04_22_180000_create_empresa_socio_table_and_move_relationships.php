<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_socio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('socio_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['empresa_id', 'socio_id']);
        });

        if (Schema::hasColumn('socios', 'empresa_id')) {
            $now = now();

            DB::table('socios')
                ->select('id', 'empresa_id')
                ->whereNotNull('empresa_id')
                ->get()
                ->each(function ($row) use ($now) {
                    DB::table('empresa_socio')->updateOrInsert(
                        [
                            'empresa_id' => $row->empresa_id,
                            'socio_id' => $row->id,
                        ],
                        [
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                });

            Schema::table('socios', function (Blueprint $table) {
                $table->dropForeign(['empresa_id']);
            });

            Schema::table('socios', function (Blueprint $table) {
                $table->dropColumn('empresa_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('socios', 'empresa_id')) {
            Schema::table('socios', function (Blueprint $table) {
                $table->foreignId('empresa_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });

            $relaciones = DB::table('empresa_socio')->orderBy('id')->get()->groupBy('socio_id');

            foreach ($relaciones as $socioId => $items) {
                $empresaId = $items->first()->empresa_id ?? null;

                if ($empresaId) {
                    DB::table('socios')->where('id', $socioId)->update(['empresa_id' => $empresaId]);
                }
            }
        }

        Schema::dropIfExists('empresa_socio');
    }
};
