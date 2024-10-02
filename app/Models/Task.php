<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Task extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'priority_level',
        'user_id',
        'archive'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function taskAttachments() {
        return $this->hasMany(TaskAttachment::class);
    }
}
