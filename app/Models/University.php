<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente al crear o actualizar universidades.
     */
    protected $fillable = [
        'name',
        'acronym',
        'user_id',
    ];

    /**
     * Relación inversa hacia el usuario dueño de la universidad.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación 1:N: una universidad puede ofrecer varias carreras.
     */
    public function careers(): HasMany
    {
        return $this->hasMany(Career::class);
    }
}
