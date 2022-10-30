@extends('users.layout')

@section('main')

    <div class="row">
        <div class="col-lg-10">
            <h2 class="text-left">Show User</h2>
        </div>
        <div class="col-lg-2 text-center" style="margin-bottom: 10px;">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name : </strong>
                {{ $user->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email : </strong>
                {{ $user->email }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role : </strong>
                {{ $user->role }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password : </strong>
                {{ __('********') }}
            </div>
        </div>
    </div>
@endsection