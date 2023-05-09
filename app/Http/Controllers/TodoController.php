<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $todos = Todo::where(['user_id' => $userId])->get();
        return view('todo.list', ['todos' => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todo.add');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $input = $request->input();
        $input['user_id'] = $userId;
        $todoStatus = Todo::create($input);

        if ($todoStatus) {
            $message = 'Todo successfully added';
            $type = 'success';
        } else {
            $message = 'Oops, something went wrong. Todo not saved';
            $type = 'error';
        }

        return redirect('todo')->with($type, $message);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userId = Auth::user()->id;
        $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
        if (!$todo) {
            return redirect('todo')->with('error', 'Todo not found');
        }
        return view('todo.view', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userId = Auth::user()->id;
        $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
        if ($todo) {
            return view('todo.edit', ['todo' => $todo]);
        } else {
            return redirect('todo')->with('error', 'Todo not found');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $todo = Todo::find($id);
        if (!$todo) {
            return redirect('todo')->with('error', 'Todo not found.');
        }
        $input = $request->input();
        $input['user_id'] = $userId;
        $todoStatus = $todo->update($input);
        if ($todoStatus) {
            return redirect('todo')->with('success', 'Todo successfully updated.');
        } else {
            return redirect('todo')->with('error', 'Oops something went wrong. Todo not updated');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userId = Auth::user()->id;
        $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
        $respStatus = $respMsg = '';
        if (!$todo) {
            $respStatus = 'error';
            $respMsg = 'Todo not found';
        }
        $todoDelStatus = $todo->delete();
        if ($todoDelStatus) {
            $respStatus = 'success';
            $respMsg = 'Todo deleted successfully';
        } else {
            $respStatus = 'error';
            $respMsg = 'Oops something went wrong. Todo not deleted successfully';
        }
        return redirect('todo')->with($respStatus, $respMsg);
    }
}
