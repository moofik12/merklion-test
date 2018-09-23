@extends('layouts.main')
@section('content')
    <div id="app">
        <chat></chat>
    </div>
    <script src={{asset("js/app.js")}}></script>
@endsection