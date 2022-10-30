@extends('users.layout')

@section('main')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Users</h2>
        </div>
        <div class="col-lg-12 text-center" style="margin-top:10px;margin-bottom: 10px;">
            <a class="btn btn-success " href="{{ route('users.create') }}"> Add User</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @if(sizeof($users) > 0)
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Email</th>                
                <th width="280px">More</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->email }}</td>                    
                    <td>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="alert alert-alert">Empty Data</div>
    @endif

    <div>    
        {!! $users->links('pagination::bootstrap-4') !!}
    </div>

@endsection