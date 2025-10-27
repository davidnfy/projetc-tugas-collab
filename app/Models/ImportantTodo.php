<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantTodo extends Model
{
    use HasFactory;

    protected $table = 'important_todos';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
