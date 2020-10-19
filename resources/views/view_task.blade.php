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
                <div class="card-header">
                    View Task 
                    @if (Auth::user()->level > 9)
                      - <font color=red>You can edit this task because you have Level > 9</font>
                    @endif
                  </div>

                <div class="card-body">
                   <table class="table table-striped">
                          <tr>
                              <td>Task Id:</td> 
                                <td>
                                  {{ $task->id }}
                                </td>
                          </tr>
                          <tr>
                              <td>Assigned By:</td> 
                                <td>
                                  {{ $task->assigned_by }}
                                </td>
                          </tr>
                          <tr>
                              <td>Assigned To:</td> 
                                <td>
                                  @if (Auth::user()->level > 9)
                                      <select name="assigned_to" id="assigned_to">
                                          @foreach ($users as $user)
                                              @if($task->assigned_to == $user->id)
                                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                              @else
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                              @endif
                                          @endforeach
                                      </select>
                                  @else
                                      UserId-{{ $task->assigned_to}} -> Name
                                      @foreach ($users as $user)
                                        @if($task->assigned_to == $user->id)
                                          [' {{ $user->name }} ']
                                        @endif
                                      @endforeach
                                  @endif
                                </td>
                          </tr>
                          <tr>
                              <td>DeadLine:</td> 
                                <td>
                                  @if (Auth::user()->level < 5)
                                    {{ $task->deadline }}
                                  @else
                                        <input id="deadline" name="deadline" type="date" maxlength="255" class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" autocomplete="off" value='{{ $task->deadline }}'/>
                                      </td>
                                      @if ($errors->has('deadline'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('deadline') }}</strong>
                                          </span>
                                      @endif
                                  @endif
                          </tr>
                          <tr>
                              <td>Task Title:</td> 
                                <td>
                                  @if (Auth::user()->level < 5)
                                    {{ $task->title }}
                                  @else
                                      <input id="title" name="title" type="text" maxlength="255" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off" value='{{ $task->title }}'/>
                                      @if ($errors->has('title'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('title') }}</strong>
                                          </span>
                                      @endif
                                  @endif
                                </td>

                          </tr>
                      
                   </table>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
