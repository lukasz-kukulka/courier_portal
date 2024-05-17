@extends('layouts.app')

@section('add_header')
    <script src="{{ asset('js/declaration_scripts.js') }}"></script>
    @php
        $declarationFormController = app(\App\Http\Controllers\DeclarationFormController::class);
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Formularz do deklaracji CN23') }}</div>

                <div class="card-body">
                    {!! $declarationFormController->generateCN23DeclarationForm() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/declaration_styles.css') }}">
@endsection
