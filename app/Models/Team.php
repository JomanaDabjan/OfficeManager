<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| Team Model
|--------------------------------------------------------------------------
| This model represents the "teams" table in the database.
| It handles team data, relationships with projects, members, and tasks.
*/

class Team extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Mass Assignment Protection
    |--------------------------------------------------------------------------
    | The $fillable array specifies which database columns can be
    | filled safely using mass-assignment methods like Team::create()
    | or $team->update().
    */

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'team_leader_id'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationship: Team belongs to a Project (Many-to-One)
    |--------------------------------------------------------------------------
    | This method defines an Inverse One-to-Many relationship.
    | Each team belongs to one specific project (linked via project_id).
    | This project contains the project manager (manager_id).
    */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship: Team has many Members (Many-to-Many)
    |--------------------------------------------------------------------------
    | This method defines a Many-to-Many relationship using a pivot table
    | named 'team_user'. It allows a team to have multiple users (members),
    | and a user to belong to multiple teams.
    */

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship: Team has many Tasks (One-to-Many)
    |--------------------------------------------------------------------------
    | This method defines a One-to-Many relationship. 
    | A single team can be assigned multiple tasks (linked via team_id in tasks table).
    */

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship: Team belongs to a Leader (Many-to-One)
    |--------------------------------------------------------------------------
    | This method defines an Inverse One-to-Many relationship with the User model.
    | It links the team's team_leader_id to the user who acts as the team leader.
    */

    public function leader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Local Scope: Filter Teams based on request parameters
    |--------------------------------------------------------------------------
    | This scope allows us to cleanly chain filtering logic in our controller.
    | It checks if specific filters (team_name, project_id, leader_id)
    | are present in the request and applies SQL WHERE clauses dynamically.
    */

    public function scopeFilter($query, array $filters)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter by Team Name
        |--------------------------------------------------------------------------
        */

        $query->when($filters['team_name'] ?? false, function ($query, $teamName) {

            if ($teamName !== 'all') {

                $query->where('name', $teamName);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Filter by Project ID
        |--------------------------------------------------------------------------
        */

        $query->when($filters['project_id'] ?? false, function ($query, $projectId) {

            if ($projectId !== 'all' && !empty($projectId)) {

                $query->where('project_id', (int) $projectId);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Filter by Team Leader ID
        |--------------------------------------------------------------------------
        | Checks if a team leader filter is provided, not 'all', and not empty.
        */

        $query->when($filters['team_leader_id'] ?? false, function ($query, $teamLeaderId) {

            if ($teamLeaderId !== 'all' && !empty($teamLeaderId)) {

                $query->where('team_leader_id', (int) $teamLeaderId);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Local Scope: Report / Export Data
    |--------------------------------------------------------------------------
    | This scope prepares Team data for:
    | - PDF export
    | - Excel export
    | - Print report
    | - DataTables
    |
    | It loads the required relationships in advance and calculates
    | the members count using SQL instead of executing a separate
    | count query for every Team row.
    */

    public function scopeReportData($query)
    {
        return $query
            ->with([
                'project',
                'leader'
            ])
            ->withCount('members');
    }
}