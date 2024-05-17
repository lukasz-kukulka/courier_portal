@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/courier_announcement_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>{{ $announcementTitle }}</strong></div>
                <div class="card-body">
                    <div class="announcement_title">
                        {{-- {{ dd( get_defined_vars() ) }} --}}
                    </div> {{-- END announcement_title --}}
                    <div class="announcement_prices">
                        <table class="table table-sm border border-1 table-striped">
                            <thead>
                                <tr class="text-center table-info">
                                    <td colspan="4">
                                        <p class="h3 text-center"><i class="bi bi-cash"> </i>{{ __( 'base.courier_announcement_cargo_type_title_summary' ) }} <i class="bi bi-cash"> </i></p>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @php $iterator = 1 @endphp
                                @foreach( $cargo as $price )
                                    <tr class="align-middle">
                                        <th scope="row" class="col-1">{{ $iterator++ }}. </th>
                                        <td class="col-5">{{ $price->cargo_name }}</td>
                                        <td class="col-5">{{ $price->cargo_description }}</td>
                                        <td class="col-1">{{ $price->cargo_price . ' ' . $price->currency  }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div> {{-- END announcement_prices --}}

                    <div class="announcement_directions_dates">
                        <table class="table table-sm border border-1 table-striped">
                            <thead>
                                <tr class="text-center table-info">
                                    <td colspan="3">
                                        <p class="h3 text-center"><i class="bi bi-calendar-date"></i> {{ __( 'base.courier_announcement_date_title_summary' ) }} <i class="bi bi-calendar-date"></i></p>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{dd( $dates )}} --}}
                                @foreach( $dates as $key => $value )
                                    <tr class="align-middle">
                                        <td class="col-1"><strong>{{ $key + 1 }}</strong></td>
                                        <td class="col-11">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- END announcement_directions_dates --}}

                    <div class="all_post_codes_container_for_single">

                        @foreach( $postCodes as $key => $value )
                            <div class="single_post_code postcodes_{{ $key }}_summary_container">
                                <table class="table table-sm border border-1">
                                    <thead>
                                        <tr class="background_colour_header_singl table-info">
                                            <td colspan="3">
                                                <p class="h3 text-center"><i class="bi bi-signpost-split"></i> {{ __( "base.courier_announcement_post_codes_" . $key . "_title_summary" ) }} <i class="bi bi-signpost-split"></i></p>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="">
                                            @foreach( $value as $key => $postCode )

                                                <td><button type="button" class="btn btn-outline-dark postcode_button_summary"> {{ $key }} </button></td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div> {{-- END all_post_codes_container --}}
                    <div class="additional_info_container border border-1">
                        <table class="table table-sm border border-1 additional_info_table_single">
                            <thead>
                                <tr class="background_colour_header_singl table-info additional_info_header">
                                    <td colspan="3">
                                        <p class="h3 text-center"><i class="bi bi-info-circle"></i> {{ __( 'base.courier_announcement_aditional_info_title_summary' ) }} <i class="bi bi-info-circle"></i></p>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="additional_info_body">
                                    <td><p>{{ $announcement->description }}</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>{{-- END announcement_title --}}
                    <div class="gallery_container border border-1">
                        <table class="table table-sm border border-1 additional_info_table_single">
                            <thead>
                                <tr class="table-info gallery_header">
                                    <td colspan="3">
                                        <p class="h3 text-center"><i class="bi bi-image"></i> {{ __( 'base.courier_announcement_gallery_title_summary' ) }} <i class="bi bi-image"></i></p>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="gallery_body">
                                    <td><p>
                                        <div class="picture_body">
                                            @php $index = 1 @endphp
                                            @foreach ( $images as $image )
                                                <img src="{{ asset( $image ) }}" alt="{{ __( 'base.courier_announcement_picrures_name' ) . ' ' . $index++ }}">
                                            @endforeach
                                        </div>
                                    </p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="contact_container border border-1">
                        <table class="table table-sm border border-1 additional_info_table_single">
                            <thead>
                                <tr class="table-info contact_header">
                                    <td colspan="2">
                                        <p class="h3 text-center"><i class="bi bi-person-bounding-box"></i> {{ __( 'base.announcement_contact_title' ) }} <i class="bi bi-person-bounding-box"></i></p>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center contact_body"><td>
                                    @foreach ( $contactDetails as $key => $value )
                                        @if ( $value != null )
                                        @auth
                                            <p><strong>{{ __( 'base.' . $key ) . ': ' . $value  }}</strong></p>
                                        @endauth
                                        @guest
                                            <p>
                                                <strong>{{ __( 'base.' . $key ) . ': ' }}<span class="blur">{{ str_repeat( '@', strlen( $value ) ) }}</span></strong>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_window_access_limited" id="show_contact">{{ __( 'base.show' ) }}</button>
                                            </p>
                                        @endguest
                                        @endif
                                    @endforeach
                                    <x-modal_window_component id="access_limited"
                                        title="{{ __( 'base.modal_window_accsess_limited_title' ) }}"
                                        message="{{ __( 'base.modal_window_accsess_limited_message' ) }}"
                                        closeButtonText="{{ __( 'base.modal_window_close_window' ) }}"
                                        secondButtonLink="/login"
                                        secondButtonText="{{ __( 'base.login' ) }}"
                                        thirdButtonLink="/register"
                                        thirdButtonText="{{ __( 'base.register' ) }}"
                                    />
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
