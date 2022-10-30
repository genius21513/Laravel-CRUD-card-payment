@extends('users.layout')

@section('main')

<div class="row">
    <div class="col-lg-10">
        <h2 class="text-left">Add User</h2>
    </div>
    <div class="col-lg-2 text-center" style="margin-bottom: 10px;">
        <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Oops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="User Name">
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                <input type="text" name="email" class="form-control" placeholder="User Email">
            </div>
            <div class="form-group">
                <strong>Role:</strong>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="role" checked value="user"> User
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="role" value="admin"> Admin
                    </label>
                </div>
            </div>
            <div class="form-group">
                <strong>Password:</strong>
                <label class="form-control"> Default Password: P@ssword</label>
            </div>
            <!-- <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" class="form-control" placeholder="User Password">
            </div>
            <div class="form-group">
                <strong>Confirm Password:</strong>
                <input type="password" name="password_confirmation" class="form-control" placeholder="User Password Confirmation">
            </div> -->
        </div>        
        <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>

</form>
@endsection