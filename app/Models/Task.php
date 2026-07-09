<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =  ['title', 'description', 'user_id', 'project_id', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
