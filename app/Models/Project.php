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

    // It means that a project can have multiple users (employees) associated with it, and the relationship is defined through a pivot table named 'project_user'. The pivot table will contain the foreign keys for both the project and the user, along with an additional 'role' column to specify the role of the user in the project.
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role');
    }

    // It means that a project belongs to a manager, and the foreign key in the projects table (manager_id) will reference the id of the user who is the manager.
    // manager_id is not used for the relationship, but it is used to specify the foreign key in the projects table that references the id of the user who is the manager of the project.
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
