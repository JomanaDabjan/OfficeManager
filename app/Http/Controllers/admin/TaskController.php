<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\EmpTaskUpdateRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Services\TaskAttachmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected $attachmentService;

    public function __construct(TaskAttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $tasks = Task::with('assignedUser', 'project')
            ->forUser($user)
            ->when($request->filled('user_id'), function ($q) use ($request) {
                $q->forSpecificUser($request->user_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->taskStatus($request->status);
            })
            ->filterAndSearch($user, $request)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $statusCounts = Task::getStatusCounts(
            $user->role === 'employee'
                ? $user->id
                : ($request->filled('user_id') ? $request->user_id : null)
        );

        $allTitles = Task::availableTitlesFor(
            $user,
            $request->filled('user_id') ? $request->user_id : null
        );

        $data = [
            'tasks' => $tasks,
            'pendingTasks' => $statusCounts['pending'] ?? 0,
            'inProgressTasks' => $statusCounts['in_progress'] ?? 0,
            'completedTasks' => $statusCounts['completed'] ?? 0,
            'acceptedTasks' => $statusCounts['accepted'] ?? 0,
            'rejectedTasks' => $statusCounts['rejected'] ?? 0,
            'allTitles' => $allTitles,
            'allUsers' => User::availableUsersFor($user),
        ];

        return view('contents.task.Index', $data);
    }


    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['project', 'assignedUser']);

        $project = $task->project;

        return view('contents.task.Show', compact('task', 'project'));
    }


    public function create()
    {
        $this->authorize('create', Task::class);

        $user = Auth::user();

        $projects = Project::availableProjectsFor($user);

        $teams = collect();

        $users = collect();

        return view(
            'contents.task.Create',
            compact(
                'projects',
                'teams',
                'users'
            )
        );
    }


    public function store(TaskStoreRequest $request)
    {
        $this->authorize('create', Task::class);


        try {
            DB::beginTransaction();

            $data = $request->validated();

            if ($request->hasFile('attachments')) {
                $attachmentData = $this->attachmentService->uploadAttachments($request);

                if ($attachmentData) {
                    $data['attachment'] = $attachmentData;
                }
            }

            Task::create($data);

            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task created successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Task Creation Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating the task. Please try again later.')
                ->withInput();
        }
    }


    public function edit(Task $task)
    {

        $this->authorize('update', $task);
        $user = Auth::user();
        $projects = Project::availableProjectsFor($user);
        $teams = Task::teamsForProject($task->project_id);
        $users = $task->team_id
            ? Task::employeesForTeam($task->team_id)
            : User::availableUsersFor($user);
        return view(
            'contents.task.Edit',
            compact(
                'task',
                'projects',
                'teams',
                'users'
            )
        );
    }


    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['attachment'] = $this->attachmentService->handleTaskAttachments($request, $task);

            unset($data['attachments']);

            $task->update($data);

            DB::commit();

            return redirect()->route('admin.task.index')->with('success', 'Task updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Task Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while updating the task. Please try again later.')
                ->withInput();
        }
    }


    public function reject(EmpTaskUpdateRequest $request, Task $task)
    {
        $this->authorize('modifyStatus', $task);

        try {
            DB::beginTransaction();

            $task->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Task rejected successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Task Rejection Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }


    public function accept(Request $request, Task $task)
    {
        $this->authorize('modifyStatus', $task);

        try {
            DB::beginTransaction();

            $task->update([
                'status' => 'accepted',
                'rejection_reason' => null
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Task accepted successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Log Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }


    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        try {
            DB::beginTransaction();

            $this->attachmentService->deleteAttachments($task->attachment);

            $task->delete();

            DB::commit();

            if (str_contains(url()->previous(), '/admin/project/')) {
                return redirect()->back()->with('success', 'Task deleted successfully.');
            }

            return redirect()->route('admin.task.index')->with('success', 'Task deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Task Deletion Error: ' . $e->getMessage());

            return redirect()->back()->with(
                'error',
                'Something went wrong while deleting the task. Please try again later.'
            );
        }
    }


    /*
     * GET TEAMS FOR SELECTED PROJECT
     */
    public function getTeamsByProject(Project $project)
    {
        $this->authorize('view', $project);

        $teams = Task::teamsForProject($project->id);

        return response()->json($teams);
    }


    /*
     * GET EMPLOYEES FOR SELECTED TEAM
     */
    public function getEmployeesByTeam(Team $team)
    {
        $this->authorize('view', $team);

        $employees = Task::employeesForTeam($team->id);

        return response()->json($employees);
    }


    public function viewAttachment(\Illuminate\Http\Request $request)
    {
        $path = $this->attachmentService->validateAndGetPath($request);

        if (!$path) {
            abort(404, 'File not found.');
        }

        return view('contents.task.View-attachment', compact('path'));
    }
}
