<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
//use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // It means that a user can be associated with multiple projects (as an employee), and the relationship is defined through a pivot table named 'project_user'. The pivot table will contain the foreign keys for both the user and the project, along with an additional 'role' column to specify the role of the user in the project.
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('role');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // It means that a manager can manage multiple projects, and each project belongs to one manager. The foreign key in the projects table (manager_id) will reference the id of the user who is the manager.
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }
}