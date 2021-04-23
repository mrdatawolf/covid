@extends('layouts.app')
@section('pageTitle', 'Charts')
<style>
    .card {
        width: 20em;
        height: 20em;
        padding-bottom: 1em;
    }
</style>
@section('content')
    @livewire('charts.daily')
@endsection
