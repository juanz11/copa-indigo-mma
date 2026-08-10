<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'estado',
        'capacidad',
        'x',
        'y',
        'rotacion',
    ];

    public function sillasDisponibles(): int
    {
        $vendidas = (int) ($this->registrations_sum_quantity ?? 0);
        return max(0, $this->capacidad - $vendidas);
    }

    protected function casts(): array
    {
        return [
            'x' => 'decimal:2',
            'y' => 'decimal:2',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(MmaRegistration::class);
    }
}
