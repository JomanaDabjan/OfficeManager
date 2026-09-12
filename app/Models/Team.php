<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'team_leader_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'team_leader_id')
            ->where('role', 'team_leader');
    }

    public function manager()
    {
        return $this->hasOneThrough(
            User::class,
            Project::class,
            'id',
            'id',
            'project_id',
            'manager_id'
        );
    }

    public function scopeForUser($query, $user)
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'manager') {
            $query->whereHas('project', function ($subQuery) use ($user) {
                $subQuery->where('manager_id', $user->id);
            });
        } elseif ($role === 'team_leader' || $role === 'employee') {
            $query->where(function ($q) use ($user) {
                $q->where('team_leader_id', $user->id)
                    ->orWhereHas('members', function ($sub) use ($user) {
                        $sub->where('users.id', $user->id);
                    });
            });
        }

        return $query;
    }

    public static function availableNamesFor($user)
    {
        return self::query()
            ->forUser($user)
            ->pluck('name')
            ->map(function ($name) {
                // 1. إزالة الأرقام والرموز مثل أرقام الفرق (مثل Team 1)
                $clean = preg_replace('/[0-9]+/', '', $name);
                // 2. توحيد الأحرف الصغيرة وإزالة الشرطات والمسافات الزائدة
                $clean = strtolower(trim(str_replace(['-', '_'], ' ', $clean)));
                // 3. جعل الحرف الأول من كل كلمة كبيراً
                return ucwords(trim($clean));
            })
            ->unique()
            ->filter()
            ->values();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['team_name'] ?? false, function ($query, $teamName) {
            if ($teamName !== 'all') {
                // تنظيف القيمة القادمة من الطلب لتوحيدها
                $cleanSearchTeam = ucwords(
                    preg_replace(
                        '/\s+/',
                        ' ',
                        str_replace(
                            ['-', '_'],
                            ' ',
                            strtolower(
                                preg_replace('/[0-9]+/', '', $teamName)
                            )
                        )
                    )
                );

                // مطابقة اسم الفريق في قاعدة البيانات بعد توحيد الأرقام والشرطات والـ underscores والمسافات وحالة الأحرف
                $query->whereRaw(
                    "TRIM(
                        REGEXP_REPLACE(
                            REGEXP_REPLACE(
                                REPLACE(
                                    REPLACE(
                                        LOWER(name),
                                        '-',
                                        ' '
                                    ),
                                    '_',
                                    ' '
                                ),
                                '[0-9]+',
                                ''
                            ),
                            '[[:space:]]+',
                            ' '
                        )
                    ) = ?",
                    [strtolower(trim($cleanSearchTeam))]
                );
            }
        });

        $query->when($filters['project_id'] ?? false, function ($query, $projectId) {
            if ($projectId !== 'all' && !empty($projectId)) {
                $query->where('project_id', (int) $projectId);
            }
        });

        $query->when($filters['team_leader_id'] ?? false, function ($query, $teamLeaderId) {
            if ($teamLeaderId !== 'all' && !empty($teamLeaderId)) {
                $query->where('team_leader_id', (int) $teamLeaderId);
            }
        });

        return $query;
    }

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
