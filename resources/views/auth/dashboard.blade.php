@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">User Dashboard</div>

                    <div class="card-body">
                        Welcome, {{ Auth::user()->name }}!
                        <br><br>
                        You are logged in as a User.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
