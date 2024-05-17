@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/user_announcement_styles.css') }}">
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
                        @if( $isSummary )
                            <form action="{{ route('user_announcement.store') }}" method="POST" id="user_announcement_summary">
                                @csrf
                        @endif
                                <div class="form-group">
                                    <p class="alert alert-info font-weight-bold h6">
                                        <i class="bi bi-search"></i> {{ $title }} <i class="bi bi-search"></i>
                                    </p>

                                    <p class="alert alert-info text-center">
                                        <span class="font-weight-bold h4"><i class="bi bi-house-down-fill"></i> {{ __( 'base.delivery_details_title' ) }} <i class="bi bi-house-down-fill"></i></span><br>

                                        {{ __( 'base.delivery_from' ) . ' ' . __( 'base.direction_print_full_name_genitive_' . request()->country_select_post_code_sending ) . ' ' .
                                        request()->input( 'prefix_select_' . request()->country_select_post_code_sending . '_post_code_sending' ) .
                                        request()->postfix_select_post_code_sending . ' ' . request()->direction_city_post_code_sending }}<br>

                                        {{ __( 'base.delivery_to' ) . ' ' . __( 'base.direction_print_full_name_genitive_' . request()->country_select_post_code_receiving ) . ' ' .
                                        request()->input( 'prefix_select_' . request()->country_select_post_code_receiving . '_post_code_receiving' ) .
                                        request()->postfix_select_post_code_receiving . ' ' . request()->direction_city_post_code_receiving}}

                                    </p>

                                    <p class="alert alert-info text-center">
                                        <span class="font-weight-bold h4"><i class="bi bi-person-rolodex"></i> {{ __( 'base.announcement_contact_title' ) }} <i class="bi bi-person-rolodex"></i></span><br>
                                        @guest
                                            <strong><i class="bi bi-telephone-fill "></i> {{ __( 'base.phone_number' ) }}:</strong> <span class="blur">{{ str_repeat( '$', strlen( request()->phone_number ) ) }}</span>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_window_access_limited" id="show_contact">{{ __( 'base.show' ) }}</button><br>
                                            <strong><i class="bi bi-envelope-at-fill"></i> {{ __( 'base.email_address' ) }}:</strong> <span class="blur">{{ str_repeat( '@', strlen( request()->email ) ) }}</span>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_window_access_limited" id="show_contact">{{ __( 'base.show' ) }}</button><br>

                                        @endguest
                                        @auth
                                            <strong><i class="bi bi-telephone-fill "></i> {{ __( 'base.phone_number' ) }}:</strong> {{ request()->phone_number }}<br>
                                            <strong><i class="bi bi-envelope-at-fill"></i> {{ __( 'base.email_address' ) }}:</strong> {{ request()->email }}<br>
                                        @endauth
                                    </p>
                                    <x-modal_window_component id="access_limited"
                                                title="{{ __( 'base.modal_window_accsess_limited_title' ) }}"
                                                message="{{ __( 'base.modal_window_accsess_limited_message' ) }}"
                                                closeButtonText="{{ __( 'base.modal_window_close_window' ) }}"
                                                secondButtonLink="/login"
                                                secondButtonText="{{ __( 'base.login' ) }}"
                                                thirdButtonLink="/register"
                                                thirdButtonText="{{ __( 'base.register' ) }}"
                                                />


                                    @if ( request()->parcel > 0 )
                                        <table class="table table-hover table-sm table-info rounded-3 overflow-hidden">
                                            <thead>
                                                <tr>
                                                    <th colspan="{{ request()->parcel }}" class="font-weight-bold h4 text-center" ><i class="bi bi-box-seam"></i> {{ __( 'base.parcel_nominative_plural_capital' ) }} <i class="bi bi-box-seam"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        @for( $i = 0; $i < request()->parcel; $i++ )
                                                            <button type="button" class="btn btn-outline-secondary mb-2">
                                                                <strong>
                                                                    {{ request()->input( 'parcel_length_' . $i ) . __( 'base.short_centimeters' ) }}  x
                                                                    {{ request()->input( 'parcel_width_' . $i ) . __( 'base.short_centimeters' ) }}  x
                                                                    {{ request()->input( 'parcel_height_' . $i ) . __( 'base.short_centimeters' ) }} -
                                                                    {{ request()->input( 'parcel_weigh_' . $i ) . __( 'base.short_kilograms' ) }}
                                                                </strong>
                                                            </button>
                                                        @endfor
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                    @if ( request()->human > 0 )
                                        <table class="table table-hover table-sm table-info rounded-3 overflow-hidden">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold h4 text-center" ><i class="bi bi-people-fill"></i> {{ __( 'base.passengers_quantity' ) }} <i class="bi bi-people-fill"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-secondary mb-2">
                                                            <strong>
                                                                {{ __( 'base.adult_base' ) . ' : ' . request()->input( 'human_adult_0' ) }}
                                                            </strong>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary mb-2">
                                                            <strong>
                                                                {{ __( 'base.kids_base' ) . ' : ' . request()->input( 'human_kids_0' ) }}
                                                            </strong>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                    @if ( request()->pallet > 0 )
                                        <table class="table table-hover table-sm table-info rounded-3 overflow-hidden">
                                            <thead>
                                                <tr>
                                                    <th colspan="{{ request()->pallet }}" class="font-weight-bold h4 text-center" ><i class="bi bi-boxes"></i> {{ __( 'base.pallet_nominative_plural_capital' ) }} <i class="bi bi-boxes"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        @for( $i = 0; $i < request()->pallet; $i++ )
                                                            <button type="button" class="btn btn-outline-secondary mb-2">
                                                                <strong>
                                                                    {{ request()->input( 'pallet_length_' . $i ) . __( 'base.short_centimeters' ) }}  x
                                                                    {{ request()->input( 'pallet_width_' . $i ) . __( 'base.short_centimeters' ) }}  x
                                                                    {{ request()->input( 'pallet_height_' . $i ) . __( 'base.short_centimeters' ) }} -
                                                                    {{ request()->input( 'pallet_weight_' . $i ) . __( 'base.short_kilograms' ) }}
                                                                </strong>
                                                            </button>
                                                        @endfor
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                    @if ( request()->animal > 0 )
                                        <table class="table table-hover table-sm table-info rounded-3 overflow-hidden">
                                            <thead>
                                                <tr>
                                                    <th colspan="{{ request()->animal }}" class="font-weight-bold h4 text-center" ><i class="bi bi-hearts"></i> {{ __( 'base.animal_nominative_plural_capital' ) }} <i class="bi bi-hearts"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        @for( $i = 0; $i < request()->animal; $i++ )
                                                            <button type="button" class="btn btn-outline-secondary mb-2">
                                                                <strong>
                                                                    {{ request()->input( 'animal_type_' . $i ) . ': ' }}
                                                                    {{ request()->input( 'animal_weight_' . $i ) . __( 'base.short_kilograms' ) }}
                                                                </strong>
                                                                <em>{{ request()->input( 'animal_short_description_' . $i ) }}</em>
                                                            </button>
                                                        @endfor
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                    @if ( request()->other > 0 )
                                        <table class="table table-hover table-sm table-info rounded-3 overflow-hidden">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold h4 text-center" ><i class="bi bi-shuffle"></i> {{ __( 'base.others_items' ) }} <i class="bi bi-shuffle"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">
                                                        @for( $i = 0; $i < request()->other; $i++ )
                                                            <button type="button" class="btn btn-outline-secondary mb-2">
                                                                <strong>
                                                                    {{ request()->input( 'other_description_' . $i ) }}
                                                                </strong>
                                                            </button>
                                                        @endfor
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif

                                    <p class="alert alert-info text-center">
                                        <span class="font-weight-bold h4"><i class="bi bi-card-text"></i> {{ __( 'base.additional_info_for_announcement_title' ) }} <i class="bi bi-card-text"></i></span><br>
                                        {{ request()->additional_user_announcement_info }}
                                    </p>
                                </div>
                        @if( $isSummary )
                                <input type="hidden" name="announcement_data" value="{{ json_encode( request()->all() ) }}">
                                <input type="hidden" name="title" value="{{ $title }}">
                                <input type="hidden" name="is_edit_mode" value="{{ request()->input( 'is_edit_mode' ) }}">
                                {{-- {{ dd( request()->is ) }} --}}
                                @if ( request()->input( 'is_edit_mode' ) == true )
                                    <input type="hidden" name="announcement_id" value="{{ request()->input( 'announcementId' ) }}">
                                @endif
                                <div class="row mb-0">
                                    <div class="col d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary" onclick="window.history.back();"> {{ __('base.back') }} </button>
                                        <button type="submit" class="btn btn-primary">{{ __( 'base.add_announcement_button' ) }}</button>
                                    </div>
                                </div>

                            </form>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
