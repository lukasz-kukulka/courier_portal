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
                    <div class="card-header">{{ __('REGULAMIN I RODO') }}</div>
                    <div class="card-body">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum ea recusandae praesentium itaque nobis
                        doloremque assumenda quia excepturi deserunt magnam minus error aspernatur vero, aut aperiam, eligendi repellat veniam voluptate.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
