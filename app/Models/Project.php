<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class Project extends Model
{
    protected $fillable = [
        'name',
        'workspace_id',
    ];

    public function workspace(){
        return $this->belongsTo(workspace::class);
    }

    public function tasks(){
        return $this->hasMany(task::class);
    }
}
