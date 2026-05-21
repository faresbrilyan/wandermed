@extends('layouts.dashboard')

@section('page_title')
    @yield('page_title', 'Admin Dashboard')
@endsection

@section('badge_role', 'Administrator')
@section('user_name', 'Administrator')
@section('user_role', 'Super Administrator')
@section('user_initial', 'A')

@section('topbar_title')
    Pusat <span style="color:#ff7a00;">Kendali Utama</span>
@endsection

@section('sidebar_nav')
    @include('layouts.admin.sidebar')
@endsection

@section('topbar_bell')
    @include('layouts.admin.topbar_bell')
@endsection

@section('content')
    @yield('content')
@endsection
