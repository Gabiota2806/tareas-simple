<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'teacher',
        'classroom',
        'color_code',
        'is_active',
        'career_id',
        'user_id',
        'track_attendance',
        'max_absences',
        'absences_count',
        'approval_type',
        'final_grade',
    ];

    public function user(): BelongsTo
    {
    return $this->belongsTo(User::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function schedules()
    {
        return $this->hasMany(SubjectSchedule::class);
    }
}
