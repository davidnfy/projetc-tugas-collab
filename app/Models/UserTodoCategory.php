<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTodoCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    public function todos()
    {
        return $this->hasMany(UserTodo::class, 'category_id');
    }
}
