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
                        <div class="card-header">{{ __('base.courier_announcement_create_form_title') }}</div>
                        <div class="card-body">
                            <form action="{{ route('courier_announcement.generateCourierAnnouncement') }}" method="POST" id="courier_announcement_form">
                                @csrf

                                <x-input_form_component name="courier_announcement_name" type="text" />
                                <div class="bg-light p-2 border border-dark"><p class="h3 text-center">{{ __('base.courier_announcement_type_cargo_title')}}</p></div>
                                <div class="cargo_type_container">
                                    <div class="col-md-14 mb-1 border-bottom"></div>
                                    <x-input_form_component name="courier_announcement_cargo_type_name" type="text" />
                                    <x-input_form_component name="courier_announcement_cargo_type_description" type="textarea" options="" size="1" />
                                    <x-input_form_component name="courier_announcement_cargo_type_price" type="number" />
                                    <button class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg> Dodaj
                                    </button>


                                </div>
                                <div class="col-md-14 border-bottom"></div>

                                <div class="col-md-14 border-bottom"></div>
                                {{-- <x-input_form_component name="direction" type="select" :options="$directionsData" />
                                <x-input_form_component name="post_code_sending" type="text" />
                                <x-input_form_component name="post_code_receiving" type="text" />
                                <x-input_form_component name="phone_number" type="text" />
                                <x-input_form_component name="email" type="text" />
                                <x-input_form_component name="expect_sending_date" type="date" />
                                <x-input_form_component name="experience_date" type="date" /> --}}
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
