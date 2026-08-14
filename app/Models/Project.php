<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'manager_id', 'status', 'start_date', 'end_date'];

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

    /**
     * Scope a query to filter and search projects based on user roles and request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterAndSearch($query, $user, $request)
    {
        // 1. Role-based base query restriction
        if ($user->role === 'manager') {
            $query->where('manager_id', $user->id);
        } elseif ($user->role !== 'admin') {
            // Assuming employees see projects they are directly linked to via relationship
            $query->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        }

        // 2. Filter by specific title
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        // 3. Filter by manager ID (applicable for admin views)
        if ($request->filled('manager_id') && $request->manager_id !== 'all') {
            $query->where('manager_id', $request->manager_id);
        }

        // 4. Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 5. Search by title or description text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * =====================================================================
     * SCOPE: ADVANCED REPORT FILTERS (FOR REPORT CONTROLLER)
     * =====================================================================
     * Centralized query filtering logic for projects reports, supporting
     * advanced search across relationships (manager names and task titles)
     * to keep controllers clean and follow the DRY principle.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReportFilter($query, $request)
    {
        /* Filter by project title if provided and not set to 'all' */
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', 'like', '%' . trim($request->title, '.') . '%');
        }

        /* Filter by specific manager ID if provided and not set to 'all' */
        if ($request->filled('manager_id') && $request->manager_id !== 'all') {
            $query->where('manager_id', $request->manager_id);
        }

        /* Filter by project status if provided and not set to 'all' */
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        /* Global search filter to check project titles, manager names, or task titles */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('manager', function ($mQuery) use ($search) {
                        $mQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('tasks', function ($t) use ($search) {
                        $t->where('title', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }
}