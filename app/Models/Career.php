<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Career extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente al crear o actualizar carreras.
     */
    protected $fillable = [
        'name',
        'university_id',
    ];

    /**
     * Relación inversa hacia la universidad a la que pertenece la carrera.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
}
