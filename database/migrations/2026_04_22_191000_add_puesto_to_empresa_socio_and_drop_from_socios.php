<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_socio', function (Blueprint $table) {
            $table->string('puesto', 60)->nullable()->after('socio_id');
        });

        if (Schema::hasColumn('socios', 'puesto')) {
            DB::table('empresa_socio')
                ->join('socios', 'socios.id', '=', 'empresa_socio.socio_id')
                ->whereNull('empresa_socio.puesto')
                ->update([
                    'empresa_socio.puesto' => DB::raw('socios.puesto'),
                ]);

            Schema::table('socios', function (Blueprint $table) {
                $table->dropColumn('puesto');
            });
        }
    }

    public function down(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->string('puesto', 60)->nullable()->after('estatus');
        });

        $relaciones = DB::table('empresa_socio')
            ->select('socio_id', 'puesto')
            ->whereNotNull('puesto')
            ->orderBy('id')
            ->get()
            ->groupBy('socio_id');

        foreach ($relaciones as $socioId => $items) {
            $puesto = $items->first()->puesto ?? null;

            if ($puesto) {
                DB::table('socios')->where('id', $socioId)->update(['puesto' => $puesto]);
            }
        }

        Schema::table('empresa_socio', function (Blueprint $table) {
            $table->dropColumn('puesto');
        });
    }
};
