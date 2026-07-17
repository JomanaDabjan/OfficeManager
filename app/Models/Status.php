<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     * Name and color are required for identifying and styling statuses.
     */
    protected $fillable = [
        'name',
        'color'
    ];

    /**
     * Get the projects that have this status.
     * A status can be assigned to multiple projects.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the history records associated with this status.
     * Tracks when and why this status was used in projects.
     */
    public function statusHistories()
    {
        return $this->hasMany(Status_History::class);
    }
}
