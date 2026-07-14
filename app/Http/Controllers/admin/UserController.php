<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{

    // This Function is used to ensure that only users with the 'admin' role can access the methods in this controller.
    // It applies a middleware that checks the user's role before allowing access to any of the controller's actions.
    public function __construct()
    {
        // Ensure only admins can access user management
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of users filtered by role.
     */
    public function index($role)
    {
        // Fetch users based on the provided role and paginate the results to show 10 users per page.
        $users = User::where('role', $role)->paginate(10);
        return view('admin.contents.tables.ShowUsers', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the view containing the form to create a new user.
        // We can pass the roles if we want to make the form dynamic.
        return view('admin.contents.createforms.UserCreateForm');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            User::create($data);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'User created successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user' . $ex->getMessage())->withtInput();
        }
    }

    /**
     * Display the specified user details.
     */
    public function show(User $user)
    {
        return view('admin.contents.details.UserDetails', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Return the view containing the form pre-filled with user data.
        return view('admin.contents.updateforms.UserUpdateForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $user->update($data);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'User updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update user' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent the admin from deleting themselves
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete yourself.');
            }

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $ex) {
            // Log the error for debugging purposes
            \Log::error("Error deleting user {$user->id}: " . $ex->getMessage());

            // Redirect back with a user-friendly error message
            return redirect()->back()->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }
}