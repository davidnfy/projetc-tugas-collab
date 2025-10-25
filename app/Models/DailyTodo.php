<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'is_completed', 'due_date',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
