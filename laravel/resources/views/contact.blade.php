@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/welcome_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 mx-auto"> <!-- Formularz na 1/3 szerokości okna -->
            <div class="card text-center">
                <div class="card-header">{{ __('base.contact_header') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sendMail') }}">
                        @csrf
                        {{-- {{  dd( Auth::user() ) }} --}}
                        <!-- Pole Temat -->
                        <div class="form-group">
                            <label for="subject">{{ __('base.contact_subject_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" value="{{ old( 'subject' ) }}" name="subject" required>
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pole Imię -->
                        <div class="form-group">
                            <label for="first_name">{{ __('base.contact_name_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" value="{{ old( 'first_name', Auth::user()->name ?? '' ) }}" name="first_name" required>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pole Nazwisko (opcjonalne) -->
                        <div class="form-group">
                            <label for="last_name">{{ __('base.contact_surname_label') }}</label>
                            <input type="text" class="form-control" id="last_name" value="{{ old( 'last_name', Auth::user()->surname ?? '' ) }}" name="last_name">
                        </div>

                        <!-- Pole E-mail -->
                        <div class="form-group">
                            <label for="email">{{ __('base.contact_mail_label') }}<span class="text-danger mt-2"> *</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old( 'email', Auth::user()->email ?? '' ) }}" name="email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pole Numer Telefonu (opcjonalne) -->
                        <div class="form-group">
                            <label for="phone">{{ __('base.contact_tel_label') }}</label>
                            <input type="tel" class="form-control" id="phone" value="{{ old( 'phone', Auth::user()->phone_number ?? '' ) }}" name="phone">
                        </div>

                        <!-- Pole Treść Wiadomości -->
                        <div class="form-group">
                            <label for="message">{{ __('base.contact_message_label') }}<span class="text-danger mt-2"> *</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" value="{{ old( 'message', '' ) }}" name="message" rows="4" required></textarea>
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
