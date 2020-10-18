<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Paginate the authenticated user's tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // paginate the authorized user's tasks with 5 per page
        $tasks = Auth::user()
            ->tasks()
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->where('assigned_to', 'is', Auth::user()->name)
            ->paginate(5);

        // return task index view with paginated tasks
        return view('tasks', [
            'tasks' => $tasks
        ]);
    }


    public function index_create()
    {
         $users = DB::table('users')->get();
        // paginate the authorized user's tasks with 5 per page
        $tasks = Auth::user()
            ->tasks()
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->paginate(5);

        // return task index view with paginated tasks
        return view('create_task', [
            'tasks' => $tasks

        ], compact('users'));
    }

    /**
     * Store a new incomplete task for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // validate the given request
        $data = $this->validate($request, [
            'title' => 'required|string|max:255',
            'deadline' => 'required',
            'assigned_by' => 'max:255',
            'assigned_to' => 'max:255',
        ]);

        // create a new incomplete task with the given title
        Auth::user()->tasks()->create([
            'title' => $data['title'],
            'assigned_by' => Auth::user()->name,
            'user_id' => $data['assigned_to'],
            'deadline' => $data['deadline'],
            'is_complete' => false,
        ]);

        // flash a success message to the session
        session()->flash('status', 'Task Created!'.$data['assigned_to']);

        // redirect to tasks index
        return redirect('/tasks');
    }

    /**
     * Mark the given task as complete and redirect to tasks index.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Task $task)
    {
        // check if the authenticated user can complete the task
        $this->authorize('complete', $task);

        // mark the task as complete and save it
        $task->is_complete = true;
        $task->save();

        // flash a success message to the session
        session()->flash('status', 'Task Completed');

        // redirect to tasks index
        return redirect('/tasks');
    }

    public function update_unmark(Task $task)
    {

        // mark the task as un-complete and save it
        $task->is_complete = false;
        $task->save();

        // flash a success message to the session
        session()->flash('status', 'Task Unmarked!');

        // redirect to tasks index
        return redirect('/tasks');
    }


    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */

    public function delete(Task $task)
    {

        $task->delete();

        // flash a success message to the session
        session()->flash('status', 'Task deleted!');

        // redirect to tasks index
        return redirect('/tasks');
    }


}