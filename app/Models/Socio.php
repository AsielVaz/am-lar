<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Socio extends Model
{
    protected $fillable = [
        'puesto',
        'nombre',
        'direccion',
        'rfc',
        'contrasena',
        'ine_pdf',
        'csf_pdf',
        'certificado_cer',
        'llave_key',
    ];

    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'empresa_socio')
            ->withTimestamps()
            ->orderBy('nombre');
    }
}
