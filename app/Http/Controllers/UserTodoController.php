<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTodoController extends BaseTodoController
{
    use HasFactory;
     public function __construct()
    {
        parent::__construct(\App\Models\UserTodo::class);
    }
}
