<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        return Feedback::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function show(Feedback $feedback)
    {
        return $feedback->load('user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['user_id'] = Auth::id();
        $feedback = Feedback::create($validated);

        return response()->json($feedback->load('user'), 201);
    }

    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'subject' => 'sometimes|string|max:255',
            'message' => 'sometimes|string|max:1000',
            'rating' => 'sometimes|integer|min:1|max:5',
            'status' => 'sometimes|in:pending,reviewed,resolved',
        ]);

        $feedback->update($validated);

        return response()->json($feedback->load('user'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response()->json(null, 204);
    }
} 