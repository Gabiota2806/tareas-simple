<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'task_type',
        'priority',
        'is_completed',
        'status',
        'due_date',
        'task_time',
        'estimated_time',
        'reminder',
        'subject_id',
        'user_id',
        'parent_id',
        'is_deleted'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_deleted'   => 'boolean',
        'due_date'     => 'date',
    ];

    /**
     * Relación con la Materia.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relación con el Usuario Propietario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación recursiva inversa: Obtener la tarea padre (si es una subtarea).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Relación recursiva directa: Obtener todas las subtareas anidadas.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Scope para obtener solo las tareas raíz (las que no son subtareas).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope para ordenar las tareas por prioridad académica.
     * Alto > Medio > Bajo.
     */
    public function scopeByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
    }

    /**
     * Scope para excluir las tareas con borrado lógico.
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
