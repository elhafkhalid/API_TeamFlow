<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
    ];

    public function user(){
        return $this->belongsTo(user::class);
    }

    public function projects(){
        return $this->hasMany(project::class);
    }
}
