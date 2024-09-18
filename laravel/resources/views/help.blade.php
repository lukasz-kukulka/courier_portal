@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/welcome_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header">{{ __('base.help_header') }}</div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
