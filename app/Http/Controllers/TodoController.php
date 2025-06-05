<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by mood if provided
        if ($request->has('mood')) {
            $query->where('mood', $request->mood);
        }

        $todos = $query->orderBy('due_date', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $todos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:pribadi,kerja,belajar',
            'mood' => 'nullable|in:senang,sedih,stress',
            'due_date' => 'required|date',
            'completed' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo = Todo::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'status' => 'success',
            'data' => $todo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|in:pribadi,kerja,belajar',
            'mood' => 'nullable|in:senang,sedih,stress',
            'due_date' => 'sometimes|required|date',
            'completed' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'data' => $todo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo deleted successfully'
        ], 200);
    }

    /**
     * Toggle the completed status of a todo.
     */
    public function toggleComplete(Todo $todo)
    {
        $todo->completed = !$todo->completed;
        $todo->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo status toggled successfully',
            'data' => $todo
        ], 200);
    }
}