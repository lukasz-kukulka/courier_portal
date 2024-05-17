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
                            {{-- {{ dd( request() )}} --}}
                            {{-- @if ( request()->input( 'edit_mode_on') == true )
                            {{ dd( request() )}}
                            @endIf --}}
                            <form action="#" method="POST" id="courier_announcement_summary">
                                @csrf
                                @foreach ( request()->all() as $key => $value )
                                    @if ( $key != 'files' )
                                        <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                {{-- {{ dd( request() ) }} --}}
                                {{-- <p>{{ count( $imagesLinks ) }}</p> --}}
                                {{-- <input type="hidden" name="images_number" id="images_number" value="{{ count( $imagesLinks ) }}"> --}}
                                {{-- @foreach ( $imagesLinks as $key => $link )
                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $link }}">
                                    <p><small>{{ $key }} -> {{ $link }}</small></p>
                                @endforeach --}}
                                {{-- @foreach ( request()->all() as $key => $value )
                                    @if ( $key != 'files' )
                                        <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach --}}
                                @foreach ( $oldImagesLinks as $key => $link )
                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $link }}">
                                    <p><small>{{ $key }} -> {{ $link }}</small></p>
                                @endforeach
                                {{-- @foreach ( request()->all() as $key => $value )
                                    @if ( $key == 'old_image_1' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_2' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_3' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_4' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_5' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_6' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_7' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_8' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_9' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                    @if ( $key == 'old_image_10' ) <p><small>{{ $key }} = {{ $value }} </small></p> @endif
                                @endforeach --}}
                                {{-- @foreach ( request()->all() as $key => $value )
                                    @if ( $key != 'files' )
                                        <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach --}}
                                <input type="hidden" name="images_number" id="images_number" value="{{ count( $imagesLinks ) }}">
                                <input type="hidden" name="old_summary_images_number" id="old_summary_images_number" value="{{ count( $oldImagesLinks ) }}">
                                {{-- {{dd( $imagesLinks, request()->all() )}} --}}
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
                                                    @php $iterator = 1 @endphp
                                                    @foreach( $deliveryDates as $dates )
                                                            <tr>
                                                                <th scope="row" class="col-1">{{ $iterator++ }}</th>
                                                                <td class="col-11">
                                                                    {{ $dates }}
                                                                </td>
                                                            </tr>

                                                    @endforeach
                                                </div>
                                            </tbody>
                                        </table>
                                    </div> {{-- END dates_summary_container --}}

                                    <div class="full_postcodes_data_containers_summary">
                                        {{-- {{ dd( $postCodes )}} --}}
                                        @foreach( $postCodes as $key => $value )
                                            <table class="table border border-1 ">
                                                <thead>
                                                    <tr class="postcodes_{{ $key }}_title_container">
                                                        <th colspan="2" class="text-center border-1 h3"><p class="h3 text-center">{{ __( "base.courier_announcement_post_codes_" . $key . "_title_summary" ) }}</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    <tr><th>
                                                        @foreach( $value as $postCode )
                                                            <button type="button" class="btn btn-outline-dark postcode_button_summary">{{ $postCode }}</button>
                                                        @endforeach
                                                    </th></tr>
                                                </tbody>
                                            </table>
                                        @endforeach
                                    </div>{{-- END full_postcodes_data_containers_summary --}}

                                    <div class="additional_description_summary_container">
                                        @php $descriptionData = request()->input( 'additional_description_input', null ); @endphp
                                        @if( $descriptionData !== null )
                                            <table class="table border border-1 ">
                                                <thead>
                                                    <tr class="postcodes_{{ $key }}_title_container">
                                                        <th colspan="2" class="text-center border-1 h3"><p class="h3 text-center">{{ __( 'base.courier_announcement_aditional_info_title_summary' ) }}</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    <tr><th>
                                                        @php
                                                            if ( $descriptionData !== "" || $descriptionData !== null ) {
                                                                echo ( '<p>' . $descriptionData . '</p>' );
                                                            }
                                                        @endphp
                                                    </th></tr>
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>{{-- END aditional_description_summary_container --}}
                                    <div class="picture_container_summary border border-1" >
                                        @if ( count( $imagesLinks ) )
                                            <div class="picture_title">
                                                <p class="h3 text-center">{{ __( 'base.courier_announcement_pictures_title_summary' ) }}</p>
                                            </div>
                                            <div class="picture_body">
                                                @foreach ( $imagesLinks as $link )
                                                    <img class="single_picture_summary" src="{{ asset( $link ) }}" alt="aaaa">
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
                                        <button type="button" class="btn btn-primary" onclick="submitFormAfterSummary('edit')" data-action="edit" data_route="{{ route('courier_announcement.create') }}">{{ __( 'base.courier_announcement_back_to_edit' ) }}</button>
                                    </div>
                                    <div class="experience_announcement_date col-6 text-center">
                                        @if ( request()->input( 'experience_for_premium_date' ) !== "1" )
                                            <span>{{ __( 'base.courier_announcement_experience_date_summary' ) . ': ' . request()->input( 'experience_announcement_date_input' ) }} </span>
                                        @endif
                                    </div>
                                    <div class="button_confirm_announcement_summary col-3 d-flex justify-content-end">
                                        @if ( request()->input( 'edit_mode_on') == true )
                                            <button type="button" class="btn btn-primary" onclick="submitFormAfterSummary('confirm')" data-action="confirm" data_route="{{ route('courier_announcement.updateEdit') }}">{{ __( 'base.add_announcement_button' ) }}</button>
                                        @else
                                            <button type="button" class="btn btn-primary" onclick="submitFormAfterSummary('confirm')" data-action="confirm" data_route="{{ route('courier_announcement.store') }}">{{ __( 'base.add_announcement_button' ) }}</button>
                                        @endIf
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
