@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}"> --}}
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $directionsData = $JsonParserController->directionsAction();
        $cargoData = $JsonParserController->cargoAction();
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.user_announcement_card_name') }}</div>
                        <div class="card-body">
                            <form action="{{ route('user_announcement.cargoDataGenerator') }}" method="POST" id="user_announcement_form">
                                @csrf
                                <x-input_form_component name="direction" type="select" :options="$directionsData" />
                                <x-input_form_component name="post_code_sending" type="text" />
                                <x-input_form_component name="post_code_receiving" type="text" />
                                <x-input_form_component name="phone_number" type="text" />
                                <x-input_form_component name="email" type="text" />
                                <x-input_form_component name="expect_sending_date" type="date" />
                                <x-input_form_component name="experience_date" type="date" />
                                <div class="col-md-14 border-bottom"></div>

                                <div>
                                    <div class="row mb-3">
                                        <label class="form-label col-md-4 d-flex align-items-center justify-content-end" for="looking_transport_for">{{ __('base.looking_transport_for') }}</label>
                                        <div class="col-md-6">
                                            @foreach ( $cargoData[ 'cargo_types' ] as $cargo_type )
                                                <div class="form-outline row mb-3">
                                                    <div class="col-md-8">
                                                        <br><input name="{{ $cargo_type[ 'id' ] }}" type="number" id="{{ $cargo_type[ 'id' ] }}" class="form-control {{ "control_cargo_" . $cargo_type[ 'id' ] }}" value="0"/>
                                                    </div>
                                                    <label class="form-label col-md-4 mt-1" for="{{ $cargo_type[ 'id' ] }}"><br>{{ __( 'base.' . $cargo_type[ 'id' ] )}}</label>
                                                    <br>
                                                </div>
                                                <div class="col-md-14 border-bottom"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-14 border-bottom"></div><br>
                                <div class="row mb-0">
                                    <div class="text-md-end">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('base.next') }}
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
