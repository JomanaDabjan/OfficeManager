<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'budget', 'manager_id', 'status', 'start_date', 'end_date'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function scopeFilterAndSearch($query, $user, $request)
    {
        if ($user->role === 'manager') {
            $query->where('manager_id', $user->id);
        } elseif ($user->role !== 'admin') {
            $query->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        }

        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', $request->title);
        }

        if ($request->filled('manager_id') && $request->manager_id !== 'all') {
            $query->where('manager_id', $request->manager_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            if ($status === 'overdue') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '<', Carbon::today())
                    ->where(function ($q) {
                        $q->whereDoesntHave('tasks')
                            ->orWhereHas('tasks', function ($tQuery) {
                                $tQuery->whereNotIn('status', ['complete', 'completed']);
                            });
                    });
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '=', Carbon::today())
                    ->where(function ($q) {
                        $q->whereDoesntHave('tasks')
                            ->orWhereHas('tasks', function ($tQuery) {
                                $tQuery->whereNotIn('status', ['complete', 'completed']);
                            });
                    });
            } else {
                $query->where('status', $status)
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>', Carbon::today());
                    });
            }
        }

        if ($request->filled('price') && $request->price !== 'all') {
            if ($request->price === 'low') {
                $query->where('budget', '<', 1000);
            } elseif ($request->price === 'medium') {
                $query->whereBetween('budget', [1000, 5000]);
            } elseif ($request->price === 'high') {
                $query->where('budget', '>', 5000);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function scopeReportFilter($query, $request)
    {
        if ($request->filled('title') && $request->title !== 'all') {
            $query->where('title', 'like', '%' . trim($request->title, '.') . '%');
        }

        if ($request->filled('manager_id') && $request->manager_id !== 'all') {
            $query->where('manager_id', $request->manager_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            if ($status === 'overdue') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '<', Carbon::today())
                    ->where(function ($q) {
                        $q->whereDoesntHave('tasks')
                            ->orWhereHas('tasks', function ($tQuery) {
                                $tQuery->whereNotIn('status', ['complete', 'completed']);
                            });
                    });
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '=', Carbon::today())
                    ->where(function ($q) {
                        $q->whereDoesntHave('tasks')
                            ->orWhereHas('tasks', function ($tQuery) {
                                $tQuery->whereNotIn('status', ['complete', 'completed']);
                            });
                    });
            } else {
                $query->where('status', $status)
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>', Carbon::today());
                    });
            }
        }

        if ($request->price === 'low') {
            $query->where('budget', '<', 1000);
        } elseif ($request->price === 'medium') {
            $query->whereBetween('budget', [1000, 5000]);
        } elseif ($request->price === 'high') {
            $query->where('budget', '>', 5000);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

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

    /**
     * Local Scope to filter projects dynamically by status based on real-time dates.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusFilter($query, ?string $status)
    {
        if (!$status || $status === 'all') {
            return $query;
        }

        return match ($status) {
            'completed' => $query->whereIn('status', ['completed', 'complete']),

            'overdue' => $query->whereNotIn('status', ['completed', 'complete'])
                ->whereNotNull('end_date')
                ->whereDate('end_date', '<', today()),

            'due_today' => $query->whereNotIn('status', ['completed', 'complete'])
                ->whereNotNull('end_date')
                ->whereDate('end_date', today()),

            'in_progress' => $query->where('status', 'in_progress')
                ->where(function ($q) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', today());
                }),

            'pending' => $query->where('status', 'pending')
                ->where(function ($q) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>', today());
                }),

            default => $query,
        };
    }
}