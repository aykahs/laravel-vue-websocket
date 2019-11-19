@extends('layouts.app')

@section('content')
                    <chat-box :Auser="'{{ Auth::user() }}'"></chat-box>

@endsection
