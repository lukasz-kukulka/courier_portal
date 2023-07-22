@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}"> --}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        {{-- <div class="card-header">{{ __('base.accounts_form') }}</div> --}}
                        <div class="card-body">
                            <form action="{{ route('user_announcement.store') }}" method="POST" id="user_announcement_form">
                                @csrf
                                <x-input_form_component name="title" type="text" />
                                <textarea name="comment">TEST</textarea>
                                <x-input_form_component name="annuncement" type="text" />
                                <x-input_form_component name="XXXXXX" type="text" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
