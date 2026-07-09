<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable =  ['title', 'description', 'manager_id', 'status'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role');
    }
}
