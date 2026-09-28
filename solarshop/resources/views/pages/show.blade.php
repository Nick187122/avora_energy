@extends('layouts.app')

@section('content')
    @include('partials.flash')

    <div class="page-hero">
        <div class="container">
            <nav class="breadcrumb"><a href="{{ route('home') }}">Home</a> &raquo; <span>{{ $page->title }}</span></nav>
            <h1>{{ $page->title }}</h1>
        </div>
    </div>

    <div class="container" style="padding:3rem 0;max-width:960px;">
        <div class="wysiwyg">
            {!! $page->content !!}
        </div>
    </div>
@endsection