<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportantTodoController extends BaseTodoController
{
    use HasFactory;
     public function __construct()
    {
        parent::__construct(\App\Models\ImportantTodo::class);
    }
}

