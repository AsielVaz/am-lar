<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    protected $fillable = [
        'nombre',
        'rfc',
        'direccion',
        'codigo_postal',
        'estatus',
        'prioridad',
        'logo',
        'contrasena_iofacturo',
        'sitio_web',
        'telefono',
        'correo',
        'fin_dominio_web',
    ];

    public function documentos(): HasOne
    {
        return $this->hasOne(EmpresaDocumento::class);
    }

    public function socios(): BelongsToMany
    {
        return $this->belongsToMany(Socio::class, 'empresa_socio')
            ->withPivot('puesto')
            ->withTimestamps()
            ->orderBy('nombre');
    }
}
