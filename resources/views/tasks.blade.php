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

            
            <div class="card">
                <div class="card-header">Tasks</div>

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
                               <td> {{ $task->deadline }} </td>
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
