@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">user Profile</div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_image" value="{{$user->profile_image}}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $user->name) }}" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="image">Profile Image</label>
                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image"
                                name="profile_image">
                            @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if ($user->profile_image)
                            <div class="mt-1">
                                <img src="{{ asset('users/' . $user->profile_image) }}" alt="Current Profile Image"
                                    style="max-width: 200px;">
                            </div>
                            @else
                            <div class="mt-1">
                                <img src="{{ asset('users/default.jpg') }}" alt="Current Profile Image"
                                    style="max-width: 200px;">
                            </div>
                            @endif
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection