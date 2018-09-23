@extends('layouts.main')
@section('content')
    <div id="app">
        <chat :user="{{ Auth::user() }}"></chat>
    </div>
    <script src={{asset("js/app.js")}}></script>
@endsection