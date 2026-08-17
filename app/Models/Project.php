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
                    ->whereDate('end_date', '<', Carbon::today());
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '=', Carbon::today());
            } elseif ($status === 'pending') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->where(function ($q) {
                        $q->whereDate('start_date', '>', Carbon::today())
                            ->orWhereNull('end_date');
                    });
            } elseif ($status === 'in_progress') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->where(function ($q) {
                        $q->whereDate('end_date', '>=', Carbon::today())
                            ->where(function ($sub) {
                                $sub->whereDate('start_date', '<=', Carbon::today())
                                    ->orWhereNull('start_date');
                            });
                    });
            } else {
                $query->where('status', $status);
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
                    ->whereDate('end_date', '<', Carbon::today());
            } elseif ($status === 'due_today') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->whereDate('end_date', '=', Carbon::today());
            } elseif ($status === 'pending') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->where(function ($q) {
                        $q->whereDate('start_date', '>', Carbon::today())
                            ->orWhereNull('end_date');
                    });
            } elseif ($status === 'in_progress') {
                $query->whereNotIn('status', ['completed', 'complete'])
                    ->where(function ($q) {
                        $q->whereDate('end_date', '>=', Carbon::today())
                            ->where(function ($sub) {
                                $sub->whereDate('start_date', '<=', Carbon::today())
                                    ->orWhereNull('start_date');
                            });
                    });
            } else {
                $query->where('status', $status);
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
                    ->orWhereHas('manager', function ($mQuery) use ($search) {
                        $mQuery->where('name', 'like', '%' . search . '%');
                    })
                    ->orWhereHas('tasks', function ($t) use ($search) {
                        $t->where('title', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }
}