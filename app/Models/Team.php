<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'manager_id'
    ];

    /**
     * Get the manager of this team.
     * A team belongs to one manager (who is a User).
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the members associated with this team.
     * A team has many members (Users) via the team_user pivot table.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->withPivot('role');
    }

    /**
     * Get the projects associated with this team.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
