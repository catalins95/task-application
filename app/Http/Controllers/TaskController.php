<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class TaskController extends Controller
{
    /**
     * Paginate the authenticated user's tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $datetime_now = Carbon::now()->add(2, 'hours');
        $expired_tasks = 0;
        $task_array = [];

        // Carbon - primele incercari
        // in DB, date-time se salveaza in formatul 'an-luna-ziTora:minut' , trebuie scos acel "T"
        //$datetime = str_replace('T', ' ', '2020-11-02T15:31'); //lets get rid of the "T"
        //$datetime = Carbon::createFromFormat('Y-m-d H:i', $datetime);
        //echo $datetime;

        // paginate the authorized user's tasks with 10 per page
        $tasks = Auth::user()
            ->tasks()
            ->where('assigned_to', Auth::user()->id)
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->paginate(10);

        foreach ($tasks as $task) {
            $task->deadline = str_replace('T', ' ', $task->deadline); //lets get rid of the "T"
            $task->deadline = Carbon::createFromFormat('Y-m-d H:i', $task->deadline);
            //echo $task->deadline->diffInMinutes($datetime_now);

            //check if task-deadline is 'expired', if yes -> the number of tasks-expired increase with 1
            if($task->deadline->isPast()) 
            {
                if($task->is_complete == 0) 
                {
                    $expired_tasks++;
                    $task_array[$task->id] = "expired";
                }
                else $task_array[$task->id] = "alive";
            }
            else $task_array[$task->id] = "alive";
            //var_dump($task_array);
        }

        //activate the message for expired
        if($expired_tasks != 0) {
            session()->flash('tasks_expired', "You have exceeded the deadline for $expired_tasks tasks!");
        }

        // return task index view with paginated tasks
        return view('tasks', [
            'tasks' => $tasks
        ], compact('datetime_now', 'expired_tasks', 'task_array'));
    }

    public function view(Task $task)
    {
        $users = DB::table('users')->get();
        // return task index view with paginated tasks
        return view('view_task', compact('task', 'users'));
    }

    public function edit(Task $task, Request $request)
    {
        $users = DB::table('users')->get();
        //$tasks = DB::table('tables')->where('id', $id)->get();
        // validate the given request
        $data = $this->validate($request, [
            'title' => 'required|string|max:255',
            'deadline' => 'required',
            'details' => 'required',
            'assigned_by' => 'max:255',
            'assigned_to' => 'max:255',
        ]);

        // update the new task info and save it
        $task->assigned_to = $data['assigned_to'];
        $task->deadline = $data['deadline'];
        $task->title = $data['title'];
        $task->details = $data['details'];
        $task->save();

        // flash a success message to the session
        session()->flash('status', 'Task Edited !');

        // return at tasks list after editing
        //return view('view_task', compact('task', 'users'));
        return redirect('/tasks');
    }

    public function index_create()
    {
         $users = DB::table('users')->get();
        // paginate the authorized user's tasks with 10 per page
        $tasks = Auth::user()
            ->tasks()
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->paginate(10);

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
            'details' => 'required',
            'assigned_by' => 'max:255',
            'assigned_to' => 'max:255',
        ]);

        // create a new incomplete task with the given title
        Auth::user()->tasks()->create([
            'title' => $data['title'],
            'details' => $data['details'],
            'assigned_by' => Auth::user()->name,
            'deadline' => $data['deadline'],
            'assigned_to' => $data['assigned_to'],
            'is_complete' => false,
        ]);
        //Update user_id pentru ca nu imi mergea din array-ul de mai sus
        Auth::user()->tasks()
            ->orderByDesc('id')
            ->take(1)
            ->update(['user_id' => $data['assigned_to']]);

        // flash a success message to the session
        session()->flash('status', 'Task Created for UserID '.$data['assigned_to']);

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