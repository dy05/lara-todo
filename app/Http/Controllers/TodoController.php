<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $todos = Todo::query();

        if ($request->has('type')) {
            $done = $request->get('type');
            if (in_array($done, ['done', 'undone'])) {
                $todos = $todos->where('done', $done == 'done' ? 1 : 0);
            }
/*            $todos = Todo::all()->reject(function($todo) use ($request) {
                if (in_array($done = $request->get('type'), ['done', 'undone'])) {
                    return $todo->done != ($done == 'done' ? 1 : 0);
                }

                return true;
            });*/
        }

        $todos = $todos->paginate(4)->withQueryString();

        return view('todos.index', compact('todos'));
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

    public function deleteAll(Request $request)
    {
        $items = explode(',', $request->input('fields'));
        $countItems = count($items);
        if ($countItems > 0) {
            Todo::query()->whereIn('id', $items)->truncate();

            return redirect()->route('todos.index')
                ->with([
                    'success' => $countItems > 1
                        ? trans('Todos supprimes avec succes')
                        : trans('Todo supprime avec succes')
                ]);
        }

        return redirect()->route('todos.index')
            ->with(['error' => trans('Aucun todo supprime')]);
    }

    public function setManyStatus(Request $request)
    {
        $items = explode(',', $request->input('fields'));
        $countItems = count($items);
        if ($countItems > 0) {
            Todo::query()->whereIn('id', $items)
                ->update([
                    'done' => $request->has('done') && $request->get('done') == 1 ? 1 : 0
                ]);

            return redirect()->route('todos.index')
                ->with([
                    'success' => $countItems > 1
                        ? trans('Todos modifies avec succes')
                        : trans('Todo modifie avec succes')
                ]);
        }

        return redirect()->route('todos.index')
            ->with(['error' => trans('Aucun todo modifie')]);
    }

    public function setOneStatus(Todo $todo, Request $request)
    {
        if ($todo->update([
            'done' => $request->has('done') && $request->get('done') == 1 ? 1 : 0
        ])) {
            if ($request->acceptsJson()) {
                return response()->json([
                    'data' => $todo
                ]);
            }

            return redirect()->route('todos.index')
                ->with([
                    'success' => trans('Todo modifie avec succes')
                ]);
        }

        return redirect()->route('todos.index')
            ->with(['error' => trans('Aucun todo modifie')]);
    }
}
