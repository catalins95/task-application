@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hello {{ Auth::user()->name }}. 
                    <br>
                    <b>Please use the drop-down menu from the right-upper-corner.</b>
                    <br>
                    <br>
                    <br>
                    <font style="font-size: 9px">
                        <i>or you can click here
                            <a href="/home">Home</a> &nbsp;&nbsp;&nbsp;
                            <a href="/create_task">Create Task</a> &nbsp;&nbsp;&nbsp;
                            <a href="/tasks">Tasks List</a> &nbsp;&nbsp;&nbsp;
                        </i>
                    </font>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
