<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals';

    protected $fillable = [
        'arete',
        'rancho_id',
        'raza',
        'sexo',
        'fecha_nacimiento',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function rancho(): BelongsTo
    {
        return $this->belongsTo(Rancho::class);
    }

    public function registrosPeso(): HasMany
    {
        return $this->hasMany(RegistroPeso::class);
    }
}
