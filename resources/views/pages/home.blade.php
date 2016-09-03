@extends('layouts.master')

@section('title', 'Welcome')

@section('nav-header')
    @include('includes.nav-header')
@endsection

@section('header')
    @include('includes.header')
@endsection

@section('grid')
    @include('includes.grid')
@endsection

@section('aboutus')
    @include('includes.aboutus')
@endsection

@section('contactus')
    @include('includes.contactus')
@endsection

@section('sidebar')
@parent

<p>This is appended to the master sidebar.</p>
@endsection

@section('content')
<p>This is my body content.</p>
@endsection