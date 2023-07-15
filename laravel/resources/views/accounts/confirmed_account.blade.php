@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}">
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $accountName = '';
        foreach ( $JsonParserController->accountAction()[ 'accounts_types' ] as $account ) {
            if ( $account[ 'id' ] == $_POST[ 'account_type_input_id' ] ) {
                $accountName = $account[ 'name' ];
            }
        }
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.accounts_form') }}</div>
                        {{-- {{ var_dump( $accountDate ) }} --}}
                        <div class="card-body">
                            <div class="confirm_info_account">{{ __('base.account_congratulation') . $accountName }}</div>
                            <div class="confirm_info_step">{{ __('base.account_last_step') }}</div><br>
                            <form method="POST" action="{{ route('create_person_data') }}">
                                @csrf
                                {{-- <input type="hidden" name="group" value="{{ $your_choice }}"> --}}
                                <x-input_form_component name="name" type="text" />
                                <x-input_form_component name="surname" type="text" />
                                <x-input_form_component name="phone_number" type="text" />
                                <x-input_form_component name="d_o_b" type="date" />

                                @if ( $_POST[ 'account_type_input_id' ] == 'courier_pro' )
                                    <br><div class="company_section_title">{{ __('base.company_section_title') }}</div>
                                    <x-input_form_component name="company_name" type="text" />
                                    <x-input_form_component name="company_adress" type="text" />
                                    <x-input_form_component name="company_phone_number" type="text" />
                                    <x-input_form_component name="company_post_code" type="text" />
                                    <x-input_form_component name="company_city" type="text" />
                                    <x-input_form_component name="company_country" type="text" />

                                @endif
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('base.send') }}
                                        </button>
                                    </div>
                                </div>

                                @if ( $_POST[ 'account_type_input_id' ] == 'courier_pro' )
                                    {{-- dodać formularz do rejestracji firmy --}}
                                @else
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
