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
                <div class="card-header">Edit Task</div>

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
                              <td>DeadLine:</td> 
                                <td>
                                    <input id="deadline" name="deadline" type="date" maxlength="255" class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" autocomplete="off" value='{{ $task->deadline }}'/>
                                  </td>
                                  @if ($errors->has('deadline'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('deadline') }}</strong>
                                      </span>
                                  @endif
                          </tr>
                          <tr>
                              <td>Task Title:</td> 
                                <td>
                                  <input id="title" name="title" type="text" maxlength="255" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off" value='{{ $task->title }}'/>
                                  @if ($errors->has('title'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('title') }}</strong>
                                      </span>
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
