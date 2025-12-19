@extends('layouts.master')

@section('main')
    <p>Halo {{ Auth::user()->getRoleNames()->first() }}</p>
@endsection
