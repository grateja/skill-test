<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class TaskAttachment extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'task_id',
        'file_name',
        'remarks',
    ];
}
