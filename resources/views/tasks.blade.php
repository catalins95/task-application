@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('tasks_expired'))
                <script type="text/javascript">
                  alert("WARNING: You have exceeded the deadline for your tasks !");
                </script>
                <div class="alert alert-danger" role="alert">
                    {{ session('tasks_expired') }}
                </div>
            @endif

            
            <div class="card">
              <center>
                <div class="card-header">Tasks List
                    <br>
                    <b>
                      <font color='blue'>Current Date & Time </font> <font color='green'>-></font>
                      <font color='red'>{{ $datetime_now ?? '' }}</font>
                    </b>
                  </center>
                </div>

                <div class="card-body">
                   <table class="table table-striped">
                          <tr>
                              <th>Date</th>
                              <th>Task Info</th>
                              <th>Assigned By</th>
                              <th>Deadline</th>
                              <th>Actions</th>
                          </tr>
                       @foreach ($tasks as $task)
                           <tr>
                                <td>{{ $task->created_at }} </td>
                               <td>
                                  <a href='/view_task/{{ $task->id }}'>
                                     @if ($task->is_complete)
                                         <s>{{ $task->title }}</s>
                                     @else
                                         {{ $task->title }}
                                     @endif
                                  </a>
                               </td>
                               <td> {{ $task->assigned_by }} </td>
                               <td> 
                                @if ($task_array[$task->id] == 'expired')
                                  <font color='red'>⚠</font>
                                @endif
                                {{ $task->deadline }} 
                               </td>
                               <td class="text-right">

                                  <form method="POST" >
                                           @csrf
                                           @method('DELETE')
                                           <button type="submit" class="btn btn-delete-action" formaction="{{ route('delete_task', $task->id) }}">X</button>
                                           
                                       </form>
                                   @if (! $task->is_complete)
                                       <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                                           @csrf
                                           @method('PATCH')
                                           <button type="submit" class="btn btn-primary">✔</button>
                                       </form>
                                   @endif

                                   @if ($task->is_complete)
                                       <form method="POST" action="{{ route('unmark_task', $task->id) }}">
                                           @csrf
                                           @method('POST')
                                           <button type="submit" class="btn btn-delete-action">!</button>
                                       </form>
                                   @endif
                                   
                               </td>
                           </tr>
                       @endforeach
                   </table>

                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
