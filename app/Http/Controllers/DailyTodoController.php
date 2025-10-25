<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyTodoController extends BaseTodoController
{
    use HasFactory;
    public function __construct()
    {
        parent::__construct(\App\Models\DailyTodo::class);
    }
}
