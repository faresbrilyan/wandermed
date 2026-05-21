@extends('layouts.dashboard')

@section('page_title')
    @yield('page_title', 'Dashboard Faskes')
@endsection

@section('badge_role', 'Mitra Faskes')
@section('user_name', $faskes?->nama_faskes ?? $mitra?->nama_penanggung_jawab ?? 'Mitra Faskes')
@section('user_role', 'Mitra Fasilitas Kesehatan')
@section('user_initial', substr($faskes?->nama_faskes ?? $mitra?->nama_penanggung_jawab ?? 'M', 0, 1))

@section('topbar_title')
    @yield('topbar_title', 'Dashboard Operasional Faskes')
@endsection

@section('sidebar_nav')
    @include('layouts.faskes.sidebar')
@endsection

@section('topbar_bell')
    @include('layouts.faskes.topbar_bell')
@endsection

@section('content')
    @yield('content')
@endsection
