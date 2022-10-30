@extends('users.layout')

@section('main')

    <div class="row">
        <div class="col-lg-10">
            <h2 class="text-left">Edit User</h2>
        </div>
        <div class="col-lg-2 text-center" style="margin-bottom: 10px;">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
        </div>
    </div>

    
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {{ $user->name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    {{ $user->email }}
                </div>
            </div>
            <hr class="mt-1" />
            <div class="form-group">
                <strong>Role: </strong>

                @php
                    $isUser = true;
                    if ($user->role !== 'user') $isUser = false;
                @endphp

                <div class="form-check-inline">
                    <label class="form-check-label">
                        {{ $user->role }}
                        <input type="radio" class="form-check-input" name="role" value="user" @if($isUser) checked @endif /> User
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="role" value="admin" @if(!$isUser) checked @endif> Admin
                    </label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password:</strong>
                    <input type="password" name="password" value="" class="form-control" placeholder="Password">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Confirm Password:</strong>
                    <input type="password" name="password_confirmation" value="" class="form-control" placeholder="Confirm Password">
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>

    </form>
@endsection