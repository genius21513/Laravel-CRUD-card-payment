@extends('users.layout')

@section('main')

    <div class="row">
        <div class="col-lg-10">
            <h2 class="text-left">Użytkownicy</h2>
        </div>
        <div class="col-lg-2" style="margin-bottom: 10px;">
            <a class="btn btn-success" href="{{ route('users.create') }}"> Dodaj użytkownika</a>
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
                <th width="10%">No</th>
                <th width="20%">Name</th>
                <th width="20%">Email</th>
                <th width="20%">Role</th>
                <th width="%">More</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
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