@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}"> --}}
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $directionsData = $JsonParserController->directionsAction();
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user_announcement.store') }}" method="POST" id="user_announcement_form">
                                @csrf
                                <x-input_form_component name="announcement_title" type="text" />
                                <x-input_form_component name="annuncement_contetnt" type="textarea" />
                                <x-input_form_component name="direction" type="select" :options="$directionsData" />
                                <x-input_form_component name="post_code_sending" type="text" />
                                <x-input_form_component name="post_code_receiving" type="text" />
                                <x-input_form_component name="phone_number" type="text" />
                                <x-input_form_component name="expect_sending_date" type="date" />
                                <x-input_form_component name="experience_date" type="date" />
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('base.send') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
