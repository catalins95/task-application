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
                       @foreach ($tasks as $task)
                           <tr>
                               <td>
                                   @if ($task->is_complete)
                                       <s>{{ $task->title }}</s>
                                   @else
                                       {{ $task->title }}
                                   @endif
                               </td>
                               <td class="text-right">
                                   @if (! $task->is_complete)
                                       <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                                           @csrf
                                           @method('PATCH')
                                           <button type="submit" class="btn btn-primary">Complete</button>
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
