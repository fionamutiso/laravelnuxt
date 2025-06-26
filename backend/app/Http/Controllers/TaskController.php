<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();
        
        // If user is authenticated, show their tasks
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }
        // If not authenticated, show all tasks (global)
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('completed') && $request->completed !== null) {
            $query->where('completed', $request->completed);
        }
        $tasks = $query->orderBy('due_date')->paginate(10);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Set user_id if authenticated, otherwise null (global task)
        $validated['user_id'] = Auth::id();
        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        // If user is authenticated, ensure they can only update their own tasks
        if (Auth::check() && $task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'completed' => 'sometimes|boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        // If user is authenticated, ensure they can only delete their own tasks
        if (Auth::check() && $task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}