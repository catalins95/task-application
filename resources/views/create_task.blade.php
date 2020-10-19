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

            <div class="card card-new-task">
                <div class="card-header">New Task</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="form-group">
                            Task Title: <input id="title" name="title" type="text" maxlength="255" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" autocomplete="off" />
                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif

                            Task Details: <textarea id="details" name="details" type="textbox" maxlength="255" class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" autocomplete="off" rows="3" cols="50">
-
-
-
                            </textarea>
                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('details') }}</strong>
                                </span>
                            @endif

                            Task Deadline: <input id="deadline" name="deadline" type="date" maxlength="255" class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" autocomplete="off" />
                            @if ($errors->has('deadline'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('deadline') }}</strong>
                                </span>
                            @endif

                            @if (Auth::user()->level > 9)
                                Assign To: 
                                <select name="assigned_to" id="assigned_to">
                                    @foreach ($users as $user)
                                    
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="assigned_to" id="assigned_to" value="{{Auth::user()->id}}">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
