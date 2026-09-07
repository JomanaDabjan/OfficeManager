<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        // -----------------------------------------------------------------
        // 1. EXTRACT ALL FILTER PARAMETERS FROM REQUEST
        // -----------------------------------------------------------------
        // We pull all relevant filter inputs sent from the view form (name, role, position, department, status, date range).
        $filters = $request->only([
            'name',
            'role',
            'position',
            'department',
            'status',
            'date_from',
            'date_to'
        ]);

        // -----------------------------------------------------------------
        // 2. FETCH DISTINCT DROPDOWN OPTIONS FOR THE UI VIEW
        // -----------------------------------------------------------------
        // We collect unique values from the database so the filter dropdowns can dynamically populate options.
        $allNames = User::distinct()->pluck('name')->filter()->values();
        $allPositions = User::distinct()->pluck('position')->filter()->values();
        $allDepartments = User::distinct()->pluck('department')->filter()->values();

        // -----------------------------------------------------------------
        // 3. APPLY QUERY SCOPE AND PAGINATION
        // -----------------------------------------------------------------
        // We invoke our custom `scopeFilter` defined in the User model, passing the filters array,
        // then paginate the results to display 15 users per page while maintaining query strings.
        $users = User::filter($filters)->paginate(15)->appends($request->query());

        // -----------------------------------------------------------------
        // 4. RETURN THE VIEW WITH COMPACTED DATA
        // -----------------------------------------------------------------
        // Pass users collection and dropdown lists back to the Blade index view.
        return view('contents.user.Index', compact(
            'users',
            'allNames',
            'allPositions',
            'allDepartments'
        ));
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

        // Calculate various project and task metrics for the user profile view
        $activeProjectsCount = $user->projects()->where('status', 'active')->count();
        $pastProjectsCount = $user->projects()->where('status', 'completed')->count();
        $tasksCount = $user->tasks()->count();
        $completedTasksCount = $user->tasks()->where('status', 'completed')->count();

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