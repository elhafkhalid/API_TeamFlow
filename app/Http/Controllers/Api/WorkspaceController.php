<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class WorkspaceController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->workspaces,
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            ['name' => 'required|string'],
        );

        $workspace = Workspace::create([
                'name' => $request->name,
                'owner_id' => $request->user()->id,
            ]);

        return response()->json([
            'message' => 'workspace cree avec succes',
            'workspace' => $workspace,
        ]);
    }
}
