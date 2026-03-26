<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $incomingFields['user_id'] = auth()->id();
        
        Todo::create($incomingFields);

        return redirect('/');
    }

    public function update(Todo $todo, Request $request)
    {
        if (auth()->id() !== $todo->user_id) {
            return redirect('/');
        }

        $todo->update([
            'is_completed' => $request->has('is_completed')
        ]);

        return redirect('/');
    }

    public function destroy(Todo $todo)
    {
        if (auth()->id() === $todo->user_id) {
            $todo->delete();
        }

        return redirect('/');
    }
}
