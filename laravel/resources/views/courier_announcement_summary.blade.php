@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/courier_announcement_styles.css') }}">
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
                            <form action="#" method="POST" id="user_announcement_summary">
                                @csrf
                                @foreach(request()->all() as $key => $value)
                                  @if ( $key != 'files' )
                                    {{-- <p>{{ $key }}: {{ print_r($value, true) }}</p> --}}
                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $value }}">
                                  @endif
                                @endforeach
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
                                                            @php
                                                                $cargoNameInput = request()->input('cargo_name_' . $i );
                                                                $cargoDescriptionInput = request()->input('cargo_description_' . $i );
                                                                $cargoPriceInput = request()->input('cargo_price_' . $i );
                                                            @endphp
                                                            @if ( $cargoNameInput !== null && $cargoDescriptionInput !== null && $cargoPriceInput !== null )
                                                                <th scope="row" class="col-1">{{ $i }}.  </th>
                                                                <td class="col-5">{{ $cargoNameInput }}</td>
                                                                <td class="col-5">{{ $cargoDescriptionInput }}</td>
                                                                <td class="col-1">{{ $cargoPriceInput . " " . request()->input('select_currency_' . $i )  }}</td>
                                                            @endif
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
                                                    @foreach( $headerData['directions'] as $dir )
                                                    {{-- {{dd($dir)}} --}}
                                                            {{ $isFirstElement = false }}
                                                            <tr class="{{ 'direction_container_' . $dir['name'] }}">
                                                                <th scope="row" class="col-3">{{ $dir[ 'print_name' ] }}</th>
                                                                <td class="col-9">
                                                                    @php
                                                                        for ( $i = 1; $i <= request()->input( 'date_number_visible' ); $i++ ) {
                                                                            if ( ( request()->input( "date_directions_select_" . $i ) ) == $dir[ 'print_name' ] ) {
                                                                                $comma = $isFirstElement ? ", " : "";
                                                                                $dateInput = request()->input( 'date_input_' . $i );
                                                                                $dateDescription = ( request()->input( 'date_description_' . $i ) != "" ? "( " . request()->input( 'date_description_' . $i ) . " )" : "" );
                                                                                $isFirstElement = true;
                                                                                echo( $comma . $dateInput . $dateDescription );
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" name="{{ "is_empty_" . $dir['name'] }}" id="{{ "is_empty_" . $dir['name'] }}" value="{{ $isFirstElement === false ? "false" : "true" }}">
                                                                </td>
                                                            </tr>

                                                    @endforeach
                                                </div>
                                            </tbody>
                                        </table>
                                    </div> {{-- END dates_summary_container --}}

                                    <div class="full_postcodes_data_containers_summary">
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
                                                            $postCodeArray = $headerData['postCodesPL'];
                                                        } else {
                                                            $postCodeArray = $headerData['postCodesUK'];
                                                        }
                                                        for( $i = 1; $i <= count( $postCodeArray ); $i++ ) {
                                                            $current = $postCodeArray[ $i ];
                                                            if ( request()->input( $current ) !== "0" ) {
                                                                echo( '<button type="button" class="btn btn-outline-dark postcode_button_summary">' . $current . '</button>' );
                                                            }
                                                        }
                                                    @endphp
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>{{-- END full_postcodes_data_containers_summary --}}

                                    <div class="additional_description_summary_container">
                                        @php $descriptionData = request()->input( 'additional_description_input', null ); @endphp
                                        @if( $descriptionData !== null )
                                            <div class="aditional_description_summary_title border border-1">
                                                <p class="h3 text-center">{{ __( 'base.courier_announcement_aditional_info_title_summary' ) }}</p>
                                            </div>
                                            <div class="additional_description_summary_data border border-1">
                                                @php

                                                    if ( $descriptionData !== "" || $descriptionData !== null ) {
                                                        echo ( '<p>' . $descriptionData . '</p>' );
                                                    }
                                                @endphp
                                            </div>
                                        @endif
                                    </div>{{-- END aditional_description_summary_container --}}
                                    <div class="picture_container_summary" >
                                        @if ( count( $imagesLinks ) )
                                            <div class="picture_title">
                                                <p class="h3 text-center">{{ __( 'base.courier_announcement_pictures_title_summary' ) }}</p>
                                            </div>
                                            <div class="picture_body">
                                                @foreach ( $imagesLinks as $link )
                                                    <img src="{{ asset( $link ) }}" alt="aaaa">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div> {{-- END picture_container_summary --}}
                                    <div class="summary_contact_cointainer">
                                        <div class="summary_contact_cointainer_head">
                                            <p class="h3 text-center">{{ __( 'base.courier_announcement_single_contact_title' ) }}</p>
                                        </div>
                                        <div class="summary_contact_cointainer_body">
                                            @foreach ( $contactData as $key => $value )
                                                @if( $value != null )
                                                    @php $cellName = "contact_detail_" . $key @endphp
                                                    <div class="one_line_contact form-control">
                                                        <div class="one_line_contact_left">
                                                            <p><strong>{{ __( 'base.' . $key ) }}</strong></p>
                                                        </div>
                                                        <div class="one_line_contact_right">

                                                            <p>{{ $value }}</p>
                                                            <input type="hidden" name="{{ $cellName }}" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    @endif
                                            @endforeach
                                        </div>

                                    </div>
                                </div> {{-- END full_summary_container --}}

                                <div class="bottom_summary container row">
                                    <div class="button_back_announcement_summary col-3 d-flex justify-content-start">
                                        <button type="button" class="btn btn-primary" onclick="submitFormAfterSummary('edit')" data-action="edit" data_route="{{ route('courier_announcement.editCreation') }}">{{ __( 'base.courier_announcement_back_to_edit' ) }}</button>
                                    </div>
                                    <div class="experience_announcement_date col-6 text-center">
                                        @if ( request()->input( 'experience_for_premium_date' ) !== "1" )
                                            <span>{{ __( 'base.courier_announcement_experience_date_summary' ) . ': ' . request()->input( 'experience_announcement_date_input' ) }} </span>
                                        @endif
                                    </div>
                                    <div class="button_confirm_announcement_summary col-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" onclick="submitFormAfterSummary('confirm')" data-action="confirm" data_route="{{ route('courier_announcement.store') }}">{{ __( 'base.add_announcement_button' ) }}</button>
                                    </div>
                                </div> {{-- END bottom_summary --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="{{ asset('js/courier_announcement_main_summary_script.js') }}"></script>
@endsection
