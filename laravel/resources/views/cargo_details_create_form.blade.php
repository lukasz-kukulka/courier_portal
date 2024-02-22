@extends('layouts.app')

@section('add_header')
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
                            <form action="{{ route('user_announcement_summary') }}" method="POST" id="user_announcement_form_cargo_details">
                                @csrf
                                @foreach ( $cargoData as $cargo )
                                    <x-cargo_details_component :name="$cargo[ 'cargoId' ]" :number="$cargo[ 'cargoQuantity' ]" :params="json_encode( $cargo[ 'cargoParams' ] )" />
                                @endforeach
                                <input type="hidden" name="announcement_data" value="{{ json_encode( request()->all() ) }}">
                                <input type="hidden" name="is_edit_mode" value="{{ request()->input( 'is_edit_mode' ) }}">
                                @if ( request()->input( 'is_edit_mode' ) == true )
                                    <input type="hidden" name="announcement_id" value="{{ request()->input( 'announcementId' ) }}">
                                @endif
                                <div class="row mb-0">
                                    <div class="col text-end">
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
