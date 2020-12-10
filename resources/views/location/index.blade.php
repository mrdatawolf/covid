@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-11 margin-tb">
            <div class="pull-left">
                <h2>Daily Count </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('location.create') }}" title="Create a count"> <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>County</th>
            <th>State</th>
            <th>Country</th>
            <th>Date Created</th>
            <th>Options</th>
        </tr>
        @foreach ($locations as $location)
            <tr>
                <td>{{ $location->id }}</td>
                <td>{{ $location->title }}</td>
                <td>{{ $location->county }}</td>
                <td>{{ $location->state }}</td>
                <td>{{ $location->country }}</td>
                <td>{{ $location->created_at }}</td>
                <td>
                    <form action="" method="POST">

                        <a href="{{ route('location.show', $location->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a>

                        <a href="{{ route('location.edit', $location->id) }}">
                            <i class="fas fa-edit  fa-lg"></i>
                        </a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fas fa-trash fa-lg text-danger"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $locations->links() !!}

@endsection
