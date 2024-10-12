@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/welcome_styles.css') }}">
@endsection


{{-- @php
    $json = new App\Http\Controllers\JsonParserController;
    $regularExpression = $json->getRegularExpression(); 
    
    function getMessageInput( $name ) {
        //dd( $regularExpression, $name );
        echo( $name . $regularExpression );
        if ( isset( $regularExpression[ $name ] ) && !empty( $regularExpression[ $name ] ) ) {
            return  __( 'base.' . $regularExpression[ $name ][ 'message' ] );
        }
        return null;
    }

    function getRegexInput( $name ) {
        if ( isset( $regularExpression[ $name ] ) && !empty( $regularExpression[ $name ] ) ) {
            return $tregularExpression[ $name ][ 'regex' ];
        }
        return null;
    }

@endphp --}}
{{-- {{ dd( get_defined_vars() ) }}  --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 mx-auto">
            <div class="card text-center">
                <div class="card-header">{{ __('base.contact_header') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sendMail') }}">
                        @csrf
                        
                        <!-- Pole Temat -->
                        <div class="form-group">
                            <label for="subject">{{ __('base.contact_subject_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="text" class="form_input form-control @error('subject') is-invalid @enderror"  id="subject" data-message="{{ $json[ 'subject' ][ 'message' ] }}" @if ( $json[ 'subject' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'subject' ][ 'regex' ] }}" @endif value="{{ old( 'subject' ) }}" name="subject" required>
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror 
                        </div> 

                        <!-- Pole Imię -->
                        <div class="form-group">
                            <label for="name">{{ __('base.contact_name_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="text" class="form_input form-control @error('name') is-invalid @enderror" id="name" data-message="{{ $json[ 'name' ][ 'message' ] }}" @if ( $json[ 'name' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'name' ][ 'regex' ] }}" @endif value="{{ old( 'name', Auth::user()->name ?? '' ) }}" name="name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">

                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pole Nazwisko (opcjonalne) -->
                        <div class="form-group">
                            <label for="surname">{{ __('base.contact_surname_label') }}</label>
                            <input type="text" class="form_input form-control" id="surname" data-message="{{ $json[ 'surname' ][ 'message' ] }}" @if ( $json[ 'surname' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'surname' ][ 'regex' ] }}" @endif value="{{ old( 'surname', Auth::user()->surname ?? '' ) }}" name="surname">
                        </div>

                        <!-- Pole E-mail -->
                        <div class="form-group">
                            <label for="email">{{ __('base.contact_mail_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="email" class="form_input form-control @error('email') is-invalid @enderror" id="email" data-message="{{ $json[ 'email' ][ 'message' ] }}" @if ( $json[ 'email' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'email' ][ 'regex' ] }}" @endif value="{{ old( 'email', Auth::user()->email ?? '' ) }}" name="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pole Numer Telefonu (opcjonalne) -->
                        <div class="form-group">
                            <label for="phone">{{ __('base.contact_tel_label') }}</label>
                            <input type="text" class="form_input form-control" id="phone" data-message="{{ $json[ 'phone' ][ 'message' ] }}" @if ( $json[ 'phone' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'phone' ][ 'regex' ] }}" @endif value="{{ old( 'phone', Auth::user()->phone_number ?? '' ) }}" name="phone">
                        </div>

                        <!-- Pole Treść Wiadomości -->
                        <div class="form-group">
                            <label for="message">{{ __('base.contact_message_label') }}<span class="text-danger mt-2"> *</span></label>
                            <textarea class="form_input form-control @error('message') is-invalid @enderror" id="message" data-message="{{ $json[ 'message' ][ 'message' ] }}" @if ( $json[ 'message' ][ 'regex' ] !== '' ) pattern="{{  $json[ 'message' ][ 'regex' ] }}" @endif value="{{ old( 'message', '' ) }}" name="message" rows="4" required></textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Przycisk wysyłania z lekkim odstępem -->
                        <button type="submit" class="btn btn-primary mt-3">{{ __('base.send') }}</button>
                        <!-- Tekst pola obowiązkowe -->
                        <p class="text-danger mt-2">{{ __( 'base.contact_field_required_label' ) }}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
