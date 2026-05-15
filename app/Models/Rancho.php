<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Rancho extends Model
{
    use HasFactory;

    protected $table = 'ranchos';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'propietario',
    ];

    public function animales(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
