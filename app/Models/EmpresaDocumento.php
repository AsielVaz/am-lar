<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class EmpresaDocumento extends Model
{
    protected $fillable = [
        'empresa_id',
        'acta_constitutiva_pdf',
        'asamblea_pdf',
        'registro_publico_pdf',
        'registro_publico_asamblea_pdf',
        'd32_pdf',
        'd32_vigencia_inicio',
        'sello_sat_key',
        'sello_sat_key_contrasena',
        'sello_sat_cer',
        'fiel_key',
        'fiel_key_contrasena',
        'fiel_cer',
        'comprobante_domicilio_pdf',
        'comprobante_domicilio_vigencia_inicio',
    ];

    protected $casts = [
        'd32_vigencia_inicio' => 'date',
        'comprobante_domicilio_vigencia_inicio' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getD32CaducidadAttribute(): ?Carbon
    {
        return $this->d32_vigencia_inicio?->copy()->addYear();
    }

    public function getComprobanteDomicilioCaducidadAttribute(): ?Carbon
    {
        return $this->comprobante_domicilio_vigencia_inicio?->copy()->addYear();
    }
}
