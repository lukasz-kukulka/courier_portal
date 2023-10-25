@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/courier_announcement_styles.css') }}">
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $directions = json_decode( $JsonParserController->directionsAction() );
        $courierAnnouncement = $JsonParserController->courierAnnouncementAction();
        $postCodesPL = $JsonParserController->plPostCodeAction();
        $postCodesUK = $JsonParserController->ukPostCodeAction();
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.courier_announcement_summary_title') }}</div>
                        <div class="card-body">
                            <form action="{{ route('courier_announcement.store') }}" method="POST" id="user_announcement_summary">
                                @csrf
                                <div class="info_summary_container">
                                    <p class="h3 text-center">{{ __( 'base.courier_announcement_show_info_summary' ) }}</p>
                                </div>
                                <div class="full_summary_container border border-1">

                                    <div class="title_summary_container">
                                        {{ $title }}
                                    </div>

                                    <div class="prices_summary_container">
                                        <table class="table border border-1 ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center border-1"><p class="h3 text-center">{{ __( 'base.courier_announcement_cargo_type_title_summary' ) }}</p></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <div class="courier_announcement_cargo_summary_container text-center">
                                                    @for ( $i = 1; $i <= request()->input('cargo_number_visible'); $i++ )
                                                        <tr class="align-middle">
                                                            <th scope="row" class="col-1">{{ $i }}.  </th>
                                                            <td class="col-5">{{ request()->input('cargo_name_' . $i ) }}</td>
                                                            <td class="col-5">{{ request()->input('cargo_description_' . $i ) }}</td>
                                                            <td class="col-1">{{ request()->input('cargo_price_' . $i ) . " " . request()->input('select_currency_' . $i )  }}</td>
                                                        </tr>
                                                    @endfor
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="dates_summary_container">
                                        <table class="table border border-1 ">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="text-center border-1"><p class="h3 text-center">{{ __( 'base.courier_announcement_date_title_summary' ) }}</p></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <div class="courier_announcement_date_summary_container text-center">
                                                    @foreach( $directions as $dir )
                                                        <tr>
                                                            <th scope="row" class="col-3">{{ $dir->print_name }}</th>
                                                            <td class="col-9">
                                                                @php
                                                                    $isFirstElement = false;
                                                                    for ( $i = 1; $i <= request()->input( 'date_number_visible' ); $i++ ) {
                                                                        if ( ( request()->input( "date_directions_select_" . $i ) ) == $dir->print_name ) {
                                                                            $comma = $isFirstElement ? ", " : "";
                                                                            $dateInput = request()->input( 'date_input_' . $i );
                                                                            $dateDescription = ( request()->input( 'date_description_' . $i ) != "" ? "( " . request()->input( 'date_description_' . $i ) . " )" : "" );
                                                                            $isFirstElement = true;
                                                                            echo( $comma . $dateInput . $dateDescription );
                                                                        }
                                                                    }
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                    @foreach ( $countryPostCodesData as $country )
                                        <div class="postcodes_{{ $country[ 'country_name' ] }}_summary_container border border 1">
                                            <div class="postcodes_{{ $country[ 'country_name' ] }}_title_container">
                                                <p class="h3 text-center">{{ __( "base.courier_announcement_" . $country[ 'translate_text' ] . "_summary" ) }}</p>
                                            </div>
                                            <div class="postcodes_separate_{{ $country[ 'country_name' ] }}_data_container">
                                                <p class="postcodes_separate_data">
                                                    @php
                                                        $postCodeArray = null;
                                                        if( $country[ 'country_name' ] === 'pl' ) {
                                                            $postCodeArray = $postCodesPL;
                                                        } else {
                                                            $postCodeArray = $postCodesUK;
                                                        }
                                                        for( $i = 1; $i <= count( $postCodeArray ); $i++ ) {
                                                            $current = $postCodeArray[ $i ];
                                                            if ( request()->input( $current ) !== "0" ) {
                                                                echo( $current . ' ' );
                                                            }
                                                        }
                                                    @endphp
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div> {{-- END full_summary_container --}}

                                <div class="form-group">
                                    @if(request()->isMethod('post'))
                                        <pre>{{ print_r(request()->all(), true) }}</pre>
                                    @endif
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
