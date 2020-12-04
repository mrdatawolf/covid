@extends('layouts.app')
@section('pageTitle', 'Charts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
