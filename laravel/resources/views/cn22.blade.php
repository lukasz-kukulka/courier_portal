@extends('layouts.app')

{{-- @section('add_header')
    @php
        $JsonParserControler = app(\App\Http\Controllers\JsonParserControler::class);
        $declarationData = $JsonParserControler->declarationAction();
    @endphp
@endsection --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Formularz do deklaracji CN22') }}</div>
                    <p></p>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
