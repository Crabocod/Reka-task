<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTags extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'title',
    ];
}
