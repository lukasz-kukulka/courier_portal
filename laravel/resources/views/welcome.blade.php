@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/welcome_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <a href="{{ route('user_announcement.index') }}" class="col-3 square">
            <div class="image-container"></div>
            <div class="text-container">
                {{ __( 'base.welcome_page_pack' )}}
            </div>
        </a>
        <a href="{{ route('courier_announcement.index') }}" class="col-3 square">
            <div class="image-container"></div>
            <div class="text-container">
                {{ __( 'base.welcome_page_van' )}}
            </div>
        </a>
    </div>
</div>
@endsection
