<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class RegistroPeso extends Model
{
    use HasFactory;

    protected $table = 'registro_pesos';

    protected $fillable = [
        'animal_id',
        'peso_kg',
        'confianza_porcentaje',
        'metodo_usado',
        'fecha_registro',
    ];

    protected $casts = [
        'peso_kg' => 'decimal:2',
        'confianza_porcentaje' => 'decimal:2',
        'fecha_registro' => 'date',
    ];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }
}
