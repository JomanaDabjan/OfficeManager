<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

// =========================================================================
// ADMIN USER CONTROLLER CLASS DEFINITION
// =========================================================================

/**
 * UserController handles all administrative web requests related to managing system users,
 * including listing users with filters, creating, updating, viewing, and deleting user accounts.
 */
class UserController extends Controller
{
    use AuthorizesRequests;

    // =========================================================================
    // INDEX METHOD: LIST & FILTER USERS
    // =========================================================================
    /**
     * Display a listing of users filtered dynamically using the Model Query Scope.
     *
     * @param Request $request The incoming HTTP request containing filter query parameters.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $user = Auth::user();

        $filters = $request->only([
            'name',
            'role',
            'position',
            'department',
            'status',
            'date_from',
            'date_to'
        ]);

        $allNames = User::query()
            ->visibleTo($user)
            ->distinct()
            ->pluck('name')
            ->filter()
            ->values();

        $allPositions = User::query()
            ->visibleTo($user)
            ->whereNotNull('position')
            ->pluck('position')
            ->map(function ($position) {
                // 1. إزالة الأرقام
                $clean = preg_replace('/[0-9]+/', '', $position);
                // 2. توحيد الفراغات والرموز (مثل الشرطات) وتغيير الأحرف الصغيرة
                $clean = strtolower(trim(str_replace(['-', '_'], ' ', $clean)));
                // 3. جعل الحرف الأول من كل كلمة كبيراً لتوحيد الشكل
                return ucwords(trim($clean));
            })
            ->unique() // إزالة التكرار نهائياً بعد التوحيد
            ->filter()
            ->values();

        $allDepartments = User::query()
            ->visibleTo($user)
            ->distinct()
            ->pluck('department')
            ->filter()
            ->values();

        $users = User::query()
            ->visibleTo($user)
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view(
            'contents.user.Index',
            compact(
                'users',
                'allNames',
                'allPositions',
                'allDepartments'
            )
        );
    }

    // =========================================================================
    // CREATE METHOD: SHOW CREATION FORM
    // =========================================================================
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('contents.user.Create');
    }

    // =========================================================================
    // STORE METHOD: SAVE A NEW USER
    // =========================================================================
    /**
     * Store a newly created resource in storage safely inside a database transaction.
     *
     * @param UserStoreRequest $request Form request containing validated input data.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        try {
            // Start database transaction to ensure data integrity
            DB::beginTransaction();
            $data = $request->validated();

            // Hash the password if it was provided
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            // Create the user record in the database
            User::create($data);

            // Commit transaction if successful
            DB::commit();

            return redirect()->route('admin.user.index', ['role' => $data['role']])->with('success', 'User created successfully.');
        } catch (Exception $ex) {
            // Roll back database changes if an error occurs
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user: ' . $ex->getMessage())->withInput();
        }
    }

    // =========================================================================
// SHOW METHOD: DISPLAY USER DETAILS
// =========================================================================
    /**
     * Display the specified user details along with statistics.
     *
     * @param User $user Route model-bound user instance.
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        // =========================================================================
        // GET PROJECT IDS RELATED TO THE USER
        // =========================================================================

        // Projects through teams where the user is a member
        $teamProjectIds = DB::table('teams')
            ->join('team_user', 'teams.id', '=', 'team_user.team_id')
            ->where('team_user.user_id', $user->id)
            ->pluck('teams.project_id');

        // Projects where the user is the team leader
        $ledProjectIds = DB::table('teams')
            ->where('team_leader_id', $user->id)
            ->pluck('project_id');

        // Projects where the user is the project manager
        $managedProjectIds = DB::table('projects')
            ->where('manager_id', $user->id)
            ->pluck('id');

        // Combine all project IDs and remove duplicates
        $projectIds = $teamProjectIds
            ->merge($ledProjectIds)
            ->merge($managedProjectIds)
            ->filter()
            ->unique()
            ->values();

        // =========================================================================
        // PROJECT STATISTICS
        // =========================================================================

        $activeProjectsCount = Project::query()
            ->whereIn('id', $projectIds)
            ->whereIn('status', [
                'pending',
                'in_progress'
            ])
            ->count();

        $pastProjectsCount = Project::query()
            ->whereIn('id', $projectIds)
            ->where('status', 'completed')
            ->count();

        // =========================================================================
        // TASK STATISTICS
        // =========================================================================

        $tasksCount = Task::query()
            ->where('user_id', $user->id)
            ->count();

        $completedTasksCount = Task::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // =========================================================================
        // RETURN USER SHOW VIEW
        // =========================================================================

        return view('contents.user.Show', compact(
            'user',
            'activeProjectsCount',
            'pastProjectsCount',
            'tasksCount',
            'completedTasksCount'
        ));
    }

    // =========================================================================
    // EDIT METHOD: SHOW EDIT FORM
    // =========================================================================
    /**
     * Show the form for editing the specified user.
     *
     * @param User $user Route model-bound user instance.
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('contents.user.Edit', compact('user'));
    }

    // =========================================================================
    // UPDATE METHOD: MODIFY EXISTING USER
    // =========================================================================
    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request Validated request data.
     * @param User $user The user model instance being updated.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        try {
            DB::beginTransaction();
            $data = $request->validated();

            // Handle password updating: hash if provided, otherwise remove from update payload
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            // Update user record
            $user->update($data);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update user: ' . $ex->getMessage())->withInput();
        }
    }

    // =========================================================================
    // DESTROY METHOD: DELETE A USER
    // =========================================================================
    /**
     * Remove the specified user from storage.
     *
     * @param User $user The user model instance to delete.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            // Prevent administrator from deleting their own currently logged-in account
            if ($user->id === Auth::id()) {
                return redirect()->back()->with('error', 'You cannot delete yourself.');
            }

            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error("Error deleting user {$user->id}: " . $ex->getMessage());

            return redirect()->back()->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }
}