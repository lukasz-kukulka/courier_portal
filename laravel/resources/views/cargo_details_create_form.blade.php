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
                        <div class="card-header">{{ __('base.user_announcement_card_name') }}</div>
                        <div class="card-body">
                            <form action="{{ route('user_announcement.summary') }}" method="POST" id="user_announcement_form_cargo_details">
                                @csrf
                                @php
                                    $params_data = [];
                                    $items_quantity = [];
                                @endphp
                                @foreach ($cargoData[ 'cargo_types' ] as $cargo_type )
                                    @php
                                        if ( $_POST[ $cargo_type[ 'id' ] ] > 0 ) {
                                            $quantity_element = [ 'id' => $cargo_type[ 'id' ], 'value' => $_POST[ $cargo_type[ 'id' ] ] ];
                                            array_push( $items_quantity, $quantity_element );
                                            for ( $i = 0; $i < $_POST[ $cargo_type[ 'id' ] ]; $i++ ) {
                                                array_push( $params_data,  $cargo_type );
                                            }
                                        }
                                    @endphp
                                    @if ( $_POST[ $cargo_type[ 'id' ] ] > 0 )
                                        <x-cargo_details_component :name="$cargo_type[ 'id' ]" :number="$_POST[ $cargo_type[ 'id' ] ]" :params="json_encode( $cargo_type[ 'params' ] )" />
                                    @endif
                                @endforeach
                                <input type="hidden" name="quantity" value="{{ json_encode( $items_quantity ) }}">
                                <input type="hidden" name="json_data" value="{{ json_encode( $params_data ) }}">
                                <input type="hidden" name="announcement_data" value="{{ json_encode( $announcement_data ) }}">

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
