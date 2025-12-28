<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Task::create([
            'title' => $request->title
        ]);

        return redirect()->back();
    }

    public function update(Task $task)
    {
        $task->is_completed = !$task->is_completed;
        $task->save();

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }
}
