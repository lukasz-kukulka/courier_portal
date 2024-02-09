@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/user_announcement_styles.css') }}">
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
                            <form action="{{ route('cargo_generator') }}" method="POST" id="user_announcement_form">
                                @csrf

                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger" role="alert">{{ $error }}</p>
                                @endforeach
                                <x-input_form_component name="post_code_sending" type="direction" :options="$directionsData" />
                                <x-input_form_component name="post_code_receiving" type="direction" :options="$directionsData" />

                                <x-input_form_component name="phone_number" type="text" value="{{ $userData->phone_number }}" maxLength="15" />
                                <x-input_form_component name="email" type="text" value="{{ $userData->email }}" maxLength="80"/>
                                <x-input_form_component name="expect_sending_date" type="date" />
                                <x-input_form_component name="experience_date" type="date" />
                                <div class="col-md-14 border-bottom"></div>

                                <div>
                                    <div class="row mb-3">
                                        <label class="form-label col-md-4 d-flex align-items-center justify-content-end" for="looking_transport_for">{{ __('base.looking_transport_for') }}</label>
                                        <div class="col-md-8">
                                            @if(session('isZeroItems') && session('isZeroItems') === true )
                                                <p class="alert alert-danger" role="alert">{{ __( 'base.announcement_createis_zero_items_message' ) }}</p>
                                            @endif

                                            @foreach ( $cargoData[ 'cargo_types' ] as $cargo_type )
                                                <div class="form-outline row mb-3">
                                                    <div class="col-md-8">
                                                        <br><input name="{{ $cargo_type[ 'id' ] }}" type="number" id="{{ $cargo_type[ 'id' ] }}" class="form-control {{ "control_cargo_" . $cargo_type[ 'id' ] }}"  value="{{ old( $cargo_type[ 'id' ], 0 ) }}" min="0" max="99"/>
                                                    </div>
                                                    <label class="form-label col-md-4 mt-1" for="{{ $cargo_type[ 'id' ] }}"><br>{{ __( 'base.' . $cargo_type[ 'id' ] )}}</label>
                                                    <br>
                                                </div>
                                                <div class="col-md-14 border-bottom"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <x-input_form_component name="additional_user_announcement_info" type="textarea" isRequired="" />
                                <input type="hidden" name="is_edit_mode" value="{{ $isEditMode }}">

                                @if ( $isEditMode == true )
                                    <input type="hidden" name="announcement_id" value="{{ $announcementId }}">
                                @endif
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
<script src="{{
    asset('js/user_announcement_create_scripts.js') }}"
    maxCargoNumber="<?php echo 'test_variable' ?>"
></script>
@endsection
