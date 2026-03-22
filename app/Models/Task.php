<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'priority',
        'deadline',
        'project_id',
        'assigned_to'
    ];

    public function project(){
        return $this->belongsTo(project::class);
    }

    public function assignedUser(){
        return $this->belongsTo(User::class,'assigned_to');
    }
}
