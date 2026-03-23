<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeTracker;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends Controller
{
    public function store(Request $request, $projectId)
    {

        $request->validate([
            'title' => 'required|string',
            'priority' => 'in:low,medium,hight',
            'deadline' => 'date',
            'assigned_to' => 'exists:users,id'
        ]);


        $project = Project::findOrFail($projectId);

        if ($project->workspace->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'forbiden',
            ]);
        }


        $task = Task::create([
            'title' => $request->title,
            'priority' => $request->priority ?? 'low',
            'deadline' => $request->deadline,
            'project_id' => $projectId,
            'assigned_to' => $request->assigned_to
        ]);


        return response()->json([
            'message' => 'task cree avec succes',
            'task' => $task,
        ]);
    }

    public function index(Request $request, $projectId)
    {
        $project = Project::with('tasks')->findOrFail($projectId);
        if ($project->workspace->owner_id !== $request->user()->id) {
            return Response()->json([
                'message' => 'forbiden',
            ]);
        }

        $query = Task::where('project_id', $projectId);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->get();

        return response()->json([
            'tasks' => $tasks,
        ]);
    }

    public function update(Request $request, $taskId)
    {

        $request->validate([
            'status' => 'in:todo,inProgress,done',
            'assigned_to' => 'exists:users,id'
        ]);

        $task = Task::findOrFail($taskId);

        if ($task->project->workspace->owner_id !== $request->user()->id) {
            return Response()->Json([
                'message' => 'forbiden',
            ]);
        }

        $task->update([
            'status' => $request->status,
            'assigned_to' => $request->assigned_to ?? $task->assigned_to
        ]);

        return Response()->json([
            'message' => 'status update avec succes',
            'task' => $task,
        ]);
    }

    public function timer(Request $request, $taskId)
    {
        

        $task = Task::findOrFail($taskId);

        if ($task->project->workspace->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'forbiden',
            ]);
        }

        $openTimer = TimeTracker::where('task_id', $task->id)
            ->where('user_id', $request->user()->id)
            ->whereNull('end_time')
            ->first();

        
        if ($openTimer) {

            $openTimer->end_time = now();

            $start = strtotime($openTimer->start_time);
            $end   = strtotime($openTimer->end_time);

            $openTimer->duration = $end - $start;

            $openTimer->save();

            return response()->json([
                'message' => 'timer stopped',
                'duration' => $openTimer->duration
            ]);
        }

        
        $openTimer = TimeTracker::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'start_time' => now()
        ]);
        
        return response()->json([
            'message' => 'timer started'
        ]);
    }
}
