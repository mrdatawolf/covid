@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-11 margin-tb">
            <div class="pull-left">
                <h2>Daily Count </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('countdaily.create') }}" title="Create a count"> <i class="fas fa-plus-circle"></i>
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
            <th>Count</th>
            <th>Date Created</th>
            <th>Options</th>
        </tr>
        @foreach ($countDaily as $count)
            <tr>
                <td>{{ $count->id }}</td>
                <td>{{ $count->count }}</td>
                <td>{{ $count->created_at }}</td>
                <td>
                    <form action="" method="POST">

                        <a href="{{ route('countdaily.show', $count->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a>

                        <a href="{{ route('countdaily.edit', $count->id) }}">
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

    {!! $countDaily->links() !!}

@endsection
