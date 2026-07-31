<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of users filtered by role.
     */
    public function index(Request $request)
    {
        $role = $request->input('role', 'employee');
        $users = User::where('role', $role)->paginate(10);
        return view('contents.user.employee.Index', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('contents.user.employee.Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            User::create($data);
            DB::commit();
            return redirect()->route('user.index', ['role' => $data['role']])->with('success', 'User created successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified user details.
     */
    public function show(User $user)
    {
        return view('admin.contents.user.employee.Show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.contents.user.employee.Edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            DB::commit();
            return redirect()->route('user.index', ['role' => $user->role])->with('success', 'User updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update user: ' . $ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return redirect()->back()->with('error', 'You cannot delete yourself.');
            }

            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (Exception $ex) {
            Log::error("Error deleting user {$user->id}: " . $ex->getMessage());

            return redirect()->back()->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }
}
