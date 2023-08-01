@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}"> --}}
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
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
                        <div class="card-header">{{ __('base.summary_anounncement_title') }}</div>
                        <div class="card-body">
                            <form action="{{ route('user_announcement.store') }}" method="POST" id="user_announcement_summary">
                                @csrf
                                <div class="form-group">
                                    <label for="title">{{ __( 'base.announcement_title_output')}}</label>
                                    <textarea class="form-control" id="title" name="title" rows="3" >{{ $announcement['title'] }}</textarea>
                                    <label for="short_announcemet">{{ __( 'base.announcement_short_output') }}</label>
                                    <textarea class="form-control" id="short_announcement" name="short_announcement" rows="7" >{{ $announcement['short_announcement'] }}</textarea>
                                    <label for="announcement_details">{{ __( 'base.announcement_long_output') }}</label>
                                    <textarea class="form-control" id="announcement_details" name="announcement_details" rows="9" >{{ $announcement['announcement_details'] }}</textarea>
                                    <input type="hidden" name="cargo_data" value="{{ json_encode( $announcement['cargo_data'] ) }}">
                                    <input type="hidden" name="announcement_data" value="{{ $_POST['announcement_data'] }}">
                                </div>
                                <button type="submit" class="btn btn-primary float-end mt-3">{{ __( 'base.add_announcement_button' ) }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
