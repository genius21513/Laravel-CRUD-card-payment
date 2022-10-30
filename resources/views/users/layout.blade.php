@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="margin-top: 20px;">
            <div class="card-body">
            @yield('main')
            </div>
        </div>
    </div>    
@endsection
