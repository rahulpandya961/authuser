@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    Welcome, {{ Auth::user()->name }}!
                    <br><br>
                    You are logged in as an Admin.
                </div>

                <div class="card-body">
                    <h5>Admin Profile</h5>
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Profile Image</th>
                            <td>
                                @if (Auth::user()->profile_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('users/' . Auth::user()->profile_image) }}" alt="Current Profile Image" style="max-width: 200px;">
                                    </div>
                                @else
                                <div class="mt-2">
                                        <img src="{{ asset('users/default.jpg') }}" alt="Current Profile Image" style="max-width: 200px;">
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-footer">
                    <a href="{{ route('auth.admin.products.index') }}" class="btn btn-primary">Products</a>
                    <a href="{{ route('admin.profile') }}" class="btn btn-primary">Admin Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
