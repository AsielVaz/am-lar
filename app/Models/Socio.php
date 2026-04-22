<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Socio extends Model
{
    protected $fillable = [
        'estatus',
        'nombre',
        'direccion',
        'rfc',
        'contrasena',
        'foto_usuario',
        'ine_pdf',
        'csf_pdf',
        'certificado_cer',
        'llave_key',
    ];

    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'empresa_socio')
            ->withPivot('puesto')
            ->withTimestamps()
            ->orderBy('nombre');
    }
}
