@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ( $isEdit === true )
                            <div class="card-header">{{ __('base.accounts_form_edit') }}</div>
                        @else
                            <div class="card-header">{{ __('base.accounts_form') }}</div>
                        @endif
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                        <div class="card-body">
                            {{-- @php $accType = isset( $accountType ) ? $accountType : request()->all( )[ 'account_type' ] @endphp --}}
                            @php $accType = 'full' @endphp
                            @php $route = $isEdit === true ? route('user_update_profile') : route( 'add_account_type_and_user_details' ); @endphp
                            @if ( $isEdit )
                                <div class="edit_profile_title">{{ __('base.edit_user_profile_title') }}</div><br>
                            @else
                                <div class="confirm_info_account">{{ __('base.create_account_confirmed_email_thanks') }}</div>
                                {{-- <div class="confirm_info_account">{{ __('base.account_confirmation') . ' ' . __( 'base.account_confirmation_' . $accType  ) }}</div>--}}
                                <div class="confirm_info_step">{{ __('base.account_confirmation_account_last_step') }}</div><br> 
                            @endif

                            <form method="POST" action="{{ $route }}">
                                @csrf
                                <input type="hidden" name="account_type" value="{{ $accType }}">
                                <x-input_form_component name="name" type="text" value="{{ Auth::user()->name }}" />
                                <x-input_form_component name="surname" type="text" value="{{ Auth::user()->surname }}"/>
                                <x-input_form_component name="email" type="text" value="{{ Auth::user()->email }}"/>
                                <x-input_form_component name="phone_number" type="text" value="{{ Auth::user()->phone_number }}"/>
                                <x-input_form_component name="d_o_b" type="date" value="{{ Auth::user()->d_o_b }}"/>

                                <div class="row mb-3 align-items-center">
                                    @php
                                        $isChecked = isset( $company ) ? 'checked' : '';
                                        if ( count( $errors->all() ) > 0 ) {
                                            $isChecked = array_key_exists( 'company_fields_checkbox', request()->all() ) ? 'checked' : '';
                                        }
                                    @endphp

                                    <label class="form-check-label col-md-4 col-form-label text-md-end" for="company_fields_checkbox">{{ __( 'base.account_confirmation_have_company' ) }}</label>
                                    <div class="col-md-6"><input class="form-check-input" type="checkbox" {{ $isChecked }} value="1" id="company_fields_checkbox" name="company_fields_checkbox"></div>
                                </div>

                                <div class="company_fields_container">
                                    <br><div class="company_section_title">{{ __('base.company_section_title') }}</div>
                                    <x-input_form_component name="company_name" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_name : '' }}"/>
                                    <x-input_form_component name="company_address" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_address : '' }}"/>
                                    <x-input_form_component name="company_phone_number" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_phone_number : '' }}"/>
                                    <x-input_form_component name="company_post_code" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_post_code : '' }}"/>
                                    <x-input_form_component name="company_city" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_city : '' }}"/>
                                    <x-input_form_component name="company_country" type="text" isRequired="" value="{{ isset( $company ) ? $company->company_country : '' }}"/>
                                </div>

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
<script src="{{ asset('js/accounts_confirmed_scripts.js') }}"></script>
@endsection
