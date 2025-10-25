<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTodo extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(UserTodoCategory::class, 'category_id');
    }

    protected $fillable = [
        'user_id', 'title', 'description', 'is_completed', 'due_date','category_id',
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