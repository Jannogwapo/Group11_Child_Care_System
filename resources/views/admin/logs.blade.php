@extends('layout')
@section('title', 'Logs')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/logs.css') }}">
@endsection
@section('content')

    <div class="container">
    <h1>Client Data Entry</h1>
    <div class="buttons">
        <button>All</button>
        <button>Clients</button>
        <button>Hearing</button>
        <button>Events</button>
        <button>Incidents</button>

    </div>
    </div>
@endsection 