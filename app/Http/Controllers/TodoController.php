<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::query();

        if ($request->has('type')) {
            $done = $request->get('type');
            if (in_array($done, ['done', 'undone'])) {
                $todos = $todos->where('done', $done == 'done' ? 1 : 0);
            }
        }

        return view('todos.index', [
            'todos' => $todos->get()
        ]);
    }

    public function create()
    {
        $editing = false;
        $todo = new Todo();

        return view('todos.todoForm', compact('todo', 'editing'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:todos',
            'description' => 'min:3'
        ]);

        $data = $request->all();

        $data['user_id'] = Auth::id();

        Todo::create($data);

        return redirect()->route('todos.index')->with(['success' => 'Todo added']);
    }

    public function show(Todo $todo)
    {
        return view('todos.show', compact('todo'));
    }

    public function edit(Todo $todo)
    {
        $editing = true;

        return view('todos.todoForm', compact('todo', 'editing'));
    }

    public function update(Request $request, Todo $todo)
    {
        $this->validate($request, [
            'title' => 'required|unique:todos,title,'.$todo->id,
            'description' => 'min:3'
        ]);

        $todo->update($request->all());

        return redirect()->route('todos.index')->with(['success' => 'Todo updated']);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')->with(['success' => 'Todo deleted']);
    }
}
