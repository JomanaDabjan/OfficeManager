<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = Auth::user();

        $projects = Project::with('manager')
            ->when($request->filled('user_id'), function ($q) use ($request) {
                $q->forSpecificUser($request->user_id);
            })
            ->filterAndSearch($user, $request)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $allTitles = Project::availableTitlesFor($user);

        $managers = Project::availableManagersFor($user);

        return view(
            'contents.project.Index',
            compact(
                'projects',
                'allTitles',
                'managers'
            )
        );
    }



    public function search(Request $request)
    {
        $search = $request->get('q');

        $projects = Project::when($search, function ($query, $search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
            ->get(['id', 'title']);

        return response()->json($projects);
    }



    public function create()
    {
        $managers = User::where('role', 'manager')->get();

        $this->authorize('create', Project::class);

        return view('contents.project.Create', compact('managers'));
    }



    public function store(ProjectStoreRequest $request)
    {
        $this->authorize('create', Project::class);

        try {
            DB::beginTransaction();

            Project::create($request->validated());

            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project created successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Project Creation Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating the project. Please try again later.')
                ->withInput();
        }
    }



    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['manager', 'tasks', 'teams']);

        return view('contents.project.Show', compact('project'));
    }



    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $user = Auth::user();
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'manager') {
            $managers = User::where('id', $user->id)->get();
        } else {
            $managers = User::where('role', 'manager')->get();
        }

        return view('contents.project.Edit', compact('project', 'managers'));
    }



    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        try {
            DB::beginTransaction();

            $data = $request->validated();

            if (Auth::user()->role === 'manager') {
                unset($data['manager_id']);
            }

            $project->update($data);

            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Project Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while updating the project. Please try again later.')
                ->withInput();
        }
    }



    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        try {
            DB::beginTransaction();

            $project->delete();

            DB::commit();

            return redirect()->route('admin.project.index')->with('success', 'Project deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Project Deletion Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while deleting the project. Please try again later.');
        }
    }
}