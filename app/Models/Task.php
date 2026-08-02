<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable =  ['title', 'description', 'user_id', 'project_id', 'status', 'attachment',];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // It means that a task belongs to an assigned user, and the foreign key in the tasks table (user_id) will reference the id of the user who is assigned to the task.
    // user_id means is not used for the relationship, but it is used to specify the foreign key in the tasks table that references the id of the user who is assigned to the task.
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}