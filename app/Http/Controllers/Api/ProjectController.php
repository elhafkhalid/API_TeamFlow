<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Workspace;

class ProjectController extends Controller
{
    public function store(Request $request, $workspaceId)
    {
        $request->validate(
            ['name' => 'required|string'],
        );

        $workspace = Workspace::findOrFail($workspaceId);

        if ($workspace->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'forbiden',
            ]);
        }

        $project = Project::create([
            'name' => $request->name,
            'workspace_id' => $workspaceId,
        ]);

        return response()->json([
            'project' => $project,
            'message' => 'project cree avec succes',
        ]);
    }

    public function show($id)
    {
        $project = Project::with('workspace')->findOrFail($id);
        return response()->json([
            'project' => $project,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if ($project->workspace->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'forbiden',
            ]);
        }

        $project->delete();

        return response()->json([
            'message' => 'project supprimer avec succe',
        ]);
    }
}
