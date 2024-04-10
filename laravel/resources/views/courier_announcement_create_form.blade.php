@extends('layouts.app')

@section('add_header')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- dodac do js ilosc cargo i daty ilosci zdjec weryfikacja #sema_update --}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.courier_announcement_create_form_title') }}</div>
                        <div class="card-body p-2">
                            <form action="{{ route('courier_announcement_generator') }}" method="POST" id="courier_announcement_form" enctype="multipart/form-data">
                                @csrf

                                @php $editModeOn = isset($editMode) @endphp
                                <input type="hidden" name="edit_mode_on" id="edit_mode_on" value="{{ old( "edit_mode_on", $editModeOn ) }}">
                                @if ( $editModeOn == true )
                                    <input type="hidden" name="announcement_number" id="announcement_number" value="{{ old( "announcement_number", $announcementNumber ) }}">
                                @endif

                                <div class="text-center row mb-3 error_picture_number is-invalid" role="alert" style="display: block;">
                                    @error('all_pictures_number')
                                        <button type="submit" class="btn btn-danger text-light"><strong>{{$message}}</strong></button>
                                    @enderror
                                </div>
                                {{-- {{ dd(request()) }} --}}
                                {{-- @if ($errors->any())
                                        {{ dd(request()) }}
                                @endif --}}
                                {{-- @if( old( 'images_number' ) )

                                    <input type="hidden" name="images_number" id="images_number" value="{{ old( 'images_number' ) }}">
                                    @for ( $i = 1; $i <= request()->input( 'images_number' ); $i++ )
                                        @php $key = array_search( request()->input( 'image' . $i ), request()->all() ) @endphp
                                        <input type="hidden" name="{{ $key }}" id="{{ $key }}" value="{{ request()->input( 'image' . $i ) }}">
                                        <p></p>
                                    @endfor

                                @endif --}}
                                <div class="row mb-3">
                                    <label for="courier_announcement_name" class="col-md-4 col-form-label text-md-end">{{ __('base.courier_announcement_name' ) }}</label>
                                    <div class="col-md-6">
                                        <input id="courier_announcement_name" type="text" class="form-control @error( 'courier_announcement_name' ) is-invalid @enderror" name="courier_announcement_name" value="{{ old( 'courier_announcement_name' ) }}" autocomplete="courier_announcement_name">
                                    </div>
                                </div>
                                <div class="cargo_type_container table-responsive">

                                    <table id="cargo_table" class="table border border-1 cargo_table">
                                        <thead>
                                            <tr>
                                                <th colspan="5" class="text-center border-1"><p class="h3 text-center">{{ __('base.courier_announcement_type_cargo_title')}}</p></th>
                                            </tr>
                                            <tr class="text-center">
                                                <th class="col-md-1" scope="col">{{ __( 'base.courier_announcement_cargo_type_id' ) }}</th>
                                                <th class="col-md-2" scope="col">{{ __( 'base.courier_announcement_cargo_type_name' ) }}</th>
                                                <th class="col-md-4" scope="col">{{ __( 'base.courier_announcement_cargo_type_description' ) }}</th>
                                                <th class="col-md-3" scope="col">{{ __( 'base.courier_announcement_cargo_type_price' ) }}</th>
                                                <th class="col-md-2" scope="col">{{ __( 'base.courier_announcement_cargo_type_actions' ) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cargo_body_table">

                                            @for ( $i = 1; $i <= $headerData['cargoElementNumber']; $i++ )
                                                <x-cargo_type_component id="{{ $i }}" />
                                            @endfor

                                                <table>
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="d-flex align-items-center justify-content-between cargo_type_button_container">
                                                                <div class="add_new_cargo_type_button">
                                                                    <button class="btn btn-primary add_cargo_component_btn" type="button">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                                        </svg> {{ __( 'base.courier_announcement_cargo_type_button_add' ) }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="date_travel_container table-responsive">
                                    <table class="table border border-1 ">
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="text-center border-1">
                                                    <p class="h3 text-center">{{ __('base.courier_announcement_date_title')}}</p>
                                                    <p class="date_title_info">{{ __('base.courier_announcement_date_title_info')}}</p>
                                                </th>
                                            </tr>
                                            <tr class="text-center">
                                                <th class="col-md-1" scope="col">{{ __( 'base.courier_announcement_date_id' ) }}</th>
                                                <th class="col-md-2" scope="col">{{ __( 'base.courier_announcement_date_direction_name_from' ) }}</th>
                                                <th class="col-md-2" scope="col">{{ __( 'base.courier_announcement_date_direction_name_to' ) }}</th>
                                                <th class="col-md-2" scope="col">{{ __( 'base.courier_announcement_date_start_date_name' ) }}</th>
                                                <th class="col-md-4"scope="col">{{ __( 'base.courier_announcement_date_description_name' ) }}</th>
                                                <th class="col-md-1" scope="col">{{ __( 'base.courier_announcement_date_actions' ) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ( $i = 1; $i <= $headerData['dateElementNumber']; $i++ )
                                                <x-transport_date_component id="{{ $i }}" />
                                            @endfor
                                            <div>
                                                <table>
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="d-flex align-items-center justify-content-between date_button_container">
                                                                <div class="add_new_date_button">
                                                                    <button class="btn btn-primary add_date_component_btn" type="button">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                                                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                                        </svg> {{ __( 'base.courier_announcement_date_button_add' ) }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="all_post_codes_container p-2 table-responsive">
                                    <table class="table border border-1 ">
                                        <thead>
                                            <tr>
                                                <th colspan="1" class="text-center border-1"><p class="h3 text-center">{{ __('base.post_codes_all_title')}}</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ( $headerData['allPostCodes'] as $key => $oneDirectionPostCodes )

                                                    <tr class="{{ $key }}_post_codes_single_container_title text-center">
                                                        <td>{{ __( 'base.post_codes_' . $key . '_title' ) }}</td>
                                                    </tr>
                                                    <tr class="align-middle h-100 {{ $key }}_post_codes_single_container_body">
                                                        <td>
                                                            @foreach ( $oneDirectionPostCodes as $code )
                                                                <div class="container_post_code_button_{{ $key }}_{{ $code }}">
                                                                    <button class="btn btn-secondary btn-sm post_code_button_{{ $key }}_{{ $code }}" type="button" data-toggle="collapse" data-target="#checkboxCollapse" aria-expanded="false" aria-controls="checkboxCollapse">
                                                                        <input type="hidden" name="{{ $code }}" value="0">
                                                                        <input id="post_code_checkbox_{{ $key }}_{{ $code }}" class="form-check-input @error( $code ) is-invalid @enderror" type="checkbox" value="{{ $code }}" {{ old( $code ) ? 'checked' : '' }} name="{{ $code }}">
                                                                        <label class="form-check-label" for="post_code_checkbox_{{ $key }}_{{ $code }}">
                                                                            {{ $code }}
                                                                        </label>
                                                                    </button>
                                                                </div>

                                                            @endforeach
                                                            <button type="button" class="btn btn-sm btn-success select_all_post_code_{{ $key }}">{{ __( 'base.selecet_all_post_codes_' . $key ) }}</button>
                                                            <button type="button" class="btn btn-sm btn-danger clear_all_post_code_{{ $key }}">{{ __( 'base.clear_all_post_codes_' . $key ) }}</button>
                                                        </td>
                                                    </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="experience_date_container p-2 table-responsive">
                                    <table class="table border border-1 ">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="text-center border-1"><p class="h3 text-center">{{ __('base.courier_announcement_date_experience_title')}}</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="align-middle h-100">
                                                <td>
                                                    <div class="input_experience_date_container text-center">
                                                        <input type="date" class="form-control @error( "experience_announcement_date_input" ) is-invalid @enderror" autocomplete="experience_announcement_date_input" id="experience_announcement_date_input" name="experience_announcement_date_input" value="{{ old( "experience_announcement_date_input" ) }}">
                                                        {{-- <input type="date" class="form-control @error( "date_input_" . $id ) is-invalid @enderror" id="date_input_{{ $id }}" name="date_input_{{ $id }}" value="{{ old( "date_input_" . $id  ) }}"> --}}
                                                    </div>
                                                </td>
                                                @if ( in_array( $headerData['loginUser']->account_type, $headerData['permDate'][ 'access_accounts' ] ) )
                                                    <td>
                                                        <div class="container_experience_for_premium form-check">
                                                            <input class="form-check-input" type="checkbox" value="1" {{ old( 'experience_for_premium_date' ) ? 'checked' : '' }} id="experience_for_premium_date" name="experience_for_premium_date">
                                                            <label class="form-check-label" for="experience_for_premium_date">
                                                                {{ __( 'base.perm_experience_announcement_date_info' ) }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                @elseif ( in_array( $headerData['loginUser']->account_type, $headerData['permDate'][ 'access_accounts' ] ) )
                                                    <td>
                                                        <p class="experience_date_info">{{ __( 'base.courier_announcement_experience_date_info') }}</p>
                                                    </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="courier_announcement_additional_description_container p-2 table-responsive">
                                    <table class="table border border-1 ">
                                        <thead>
                                            <tr>
                                                <th colspan="1" class="text-center border-1"><p class="h3 text-center">{{ __('base.courier_announcement_additional_description_title')}}</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="align-middle h-100">
                                                <td>
                                                    <div class="input_additional_description_container text-center">
                                                        <textarea id="additional_description_input" class="form-control @error( "additional_description_input" ) is-invalid @enderror" name="additional_description_input" autocomplete="additional_description_input" rows="3">{{ old( "additional_description_input" ) }}</textarea>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                                @php $imagesNumber = request()->input( 'old_summary_images_number' ) @endphp
                                @if ($errors->any())
                                    @php $imagesNumber = old( 'old_images_number', request()->input( 'old_images_number' ) ) @endphp
                                @endif
                                @if( $imagesNumber != null )
                                    <div class="courier_announcement_previously_pictures_container p-2 table-responsive">
                                        <table class="table border border-1 ">
                                            <thead>
                                                <tr>
                                                    <th colspan="1" class="text-center border-1"><p class="h3 text-center">{{ __('base.courier_announcement_previously_picrures_title')}}</p></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr class="input_courier_announcement_previosly_picture align-middle h-100">
                                                    <td class="picture_container_summary border border-1">
                                                        <div class="prev_picture_body">

                                                            @for ( $i = 1; $i <= $imagesNumber; $i++ )
                                                                <div class="{{ 'delete_image_background_' . $i }}">
                                                                    <div class="single_prev_picture_container border border-1">
                                                                        <div class="top_single_prev_picture_container">
                                                                            <div class="left_single_prev_picture_container">
                                                                                @php $link = asset( request()->input( 'image' . $i ) ) @endphp
                                                                                @if ($errors->any())
                                                                                    @php $link = asset( old( 'old_image_' . $i, request()->input( 'old_image_' . $i ) ) ) @endphp
                                                                                @endif
                                                                                <img class="single_prev_picture" src="{{ $link }}" alt="{{ 'image' . $i }}">
                                                                            </div>
                                                                            <div class="right_single_prev_picture_container">
                                                                                <button id="{{"delete_prev_image_" . $i }}" type="button" class="{{"delete_prev_image_" . $i }} btn btn-primary">{{ __( 'base.courier_announcement_delete_prev_image_button' ) }}</button>
                                                                                <button disabled id="{{"restore_prev_image_" . $i }}" type="button" class="{{"restore_prev_image_" . $i }} btn btn-secondary">{{ __( 'base.courier_announcement_restore_prev_image_button' ) }}</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="bottom_single_prev_picture_container">
                                                                            <p class="{{ 'image_will_be_delete_note_' . $i }}">{{ __( 'base.courier_announcement_image_will_be_delete_note' ) }}</p>
                                                                        </div>
                                                                    </div>
                                                                    @if ($errors->any())
                                                                        <input class="{{ 'old_image_link_' . $i }}" type="hidden" name="{{ 'old_image_' . $i }}" value="{{ old( 'old_image_' . $i, request()->input( 'image' . $i ) ) }}">
                                                                        {{-- <input class="{{ 'old_image_info_' . $i }}" type="hidden" name="{{ 'old_image_info_' . $i }}" value="{{ old( 'old_image_info_' . $i, 'noDelete' ) }}"> --}}
                                                                    @else
                                                                        <input class="{{ 'old_image_link_' . $i }}" type="hidden" name="{{ 'old_image_' . $i }}" value="{{ request()->input( 'image' . $i ) }}">
                                                                        {{-- <input class="{{ 'old_image_info_' . $i }}" type="hidden" name="{{ 'old_image_info_' . $i }}" value="noDelete"> --}}
                                                                    @endif
                                                                    <input class="{{ 'old_image_info_' . $i }}" type="hidden" name="{{ 'old_image_info_' . $i }}" value="{{ old( 'old_image_info_' . $i, 'noDelete' ) }}">
{{--
                                                                    <p><small>OLD -> {{ old( 'old_image_info_' . $i, 'noDelete' ) }} => {{ old( 'old_image_' . $i, request()->input( 'image' . $i ) ) }}</small></p>
                                                                    <p><small>NEW {{ request()->input( 'old_image_info_' . $i ) }} => {{ request()->input( 'image' . $i ) }}</small></p>
                                                                    <small></small>
                                                                    <p>------------------------------------------------</p> --}}

                                                                </div>
                                                            @endfor
                                                            <input class="old_images_number" type="hidden" name="old_images_number" value="{{ $imagesNumber }}">
                                                        </div>
                                                    </td>{{-- END picture_container_summary --}}
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                {{-- <input class="test" type="hidden" name="test.test.test" value="test.test.test">
                                @foreach( request()->all() as $key => $value )
                                @if ( $key == 'image1' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image2' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image3' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image4' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image5' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image6' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image7' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image8' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image9' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image10' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image11' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'image12' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_1' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_2' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_3' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_4' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_5' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_6' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_7' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_8' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_9' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_10' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_11' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'old_image_12' ) <p>{{ $key }} = {{ $value }} </p> @endif --}}

{{--

                                @if ( $key == 'images_number' ) <p>{{ $key }} = {{ $value }} </p> @endif
                                @if ( $key == 'all_pictures_number' ) <p>{{ $key }} = {{ $value }} </p> @endif

                                @endforeach
                                <p>INFO: {{ old( 'old_image_info_1', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_2', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_3', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_4', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_5', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_6', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_7', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_8', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_9', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_10', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_11', 'noDelete' ) }}</p>
                                <p>INFO: {{ old( 'old_image_info_12', 'noDelete' ) }}</p>
                                <p>OLD ========{{ old( 'images_number', request()->input( 'images_number' ) ) }}</p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_1' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_2' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_3' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_4' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_5' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_6' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_7' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_8' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_9' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_10' ) }} </p>
                                <p>{{ 'old_image' }} = {{ old( 'old_image_11' ) }} </p>

                                <p>{{ 'image' }} = {{ old( 'image1' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image2' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image3' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image4' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image5' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image6' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image7' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image8' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image9' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image10' ) }} </p>
                                <p>{{ 'image' }} = {{ old( 'image11' ) }} </p> --}}
                                {{-- @if(session()->hasOldInput())
                                    <ul>
                                        @foreach(old() as $key => $value)
                                            <li>{{ $key }}: {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                                <div class="courier_announcement_pictures_container p-2 table-responsive">
                                    <table class="table border border-1 ">
                                        <thead>
                                            <tr>
                                                <th colspan="1" class="text-center border-1"><p class="h3 text-center">{{ __('base.courier_announcement_picrures_title')}}</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ( count( $errors->all() ) > 0 && old( "is_error_picture_info" ) )
                                                <tr>
                                                    <td>
                                                        <div class="error_picture_info">{{ __( 'base.courier_announcement_error_picture_info' )}}</div>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr class="input_courier_announcement_picture align-middle h-100">
                                                <td>
                                                    <p class="picture_limit_info">{{ __( 'base.courier_announcement_picture_limit_info' ) . $headerData['picturesNumber'] }}</p>
                                                    <div class="form-group input_pictures">
                                                        <label for="courier_announcement_picture_input">{{ __( 'base.courier_announcement_picrures_name' )}}</label>
                                                        <input type="file" multiple class="form-control-file" accept="{{ $extensions }}" id="courier_announcement_picture_input" name="files[]" onchange="displayThumbnails(event)">
                                                    </div>

                                                    <div class="thumbnailsContainer">

                                                        <div class="thumbnails_picture">

                                                        </div>
                                                        <div class="thubnails_actions">

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="contact_container border border-1">
                                    <table class="table table-sm border border-1 additional_info_table_single">
                                        <thead>
                                            <tr class="table-info contact_header">
                                                <td colspan="3">
                                                    <p class="h3 text-center">{{ __( 'base.courier_announcement_contact_create_title' ) }}</p>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="contact_body">
                                                <td><p>
                                                    <div class="picture_body">
                                                        <div class="fill_data_contact_buttons_container">
                                                            <div class="fill_data_contact_buttons_left">
                                                                <button id="courier_announcement_fill_data_contact_button" type="button" class="btn btn-primary">{{ __( 'base.courier_announcement_contact_fill_button' ) }}</button>
                                                            </div>
                                                            <div class="fill_data_contact_buttons_right">
                                                                <button id="courier_announcement_clear_data_contact_button" type="button" class="btn btn-primary">{{ __( 'base.courier_announcement_contact_clear_button' ) }}</button>
                                                            </div>
                                                        </div>

                                                        @foreach ( $contactData as $key => $value )
                                                            @php $cellName = "contact_detail_" . $key @endphp
                                                            <div class="one_line_contact form-control">
                                                                <div class="one_line_contact_left">
                                                                    <label class="col-form-label text-md-end" for="{{ $cellName }}"><strong>{{ __( 'base.' . $key ) }}</strong></label>
                                                                </div>
                                                                <div class="one_line_contact_right">
                                                                    <input class="form-group form-control @error( $cellName ) is-invalid @enderror" type="text" id="{{ $cellName }}" name="{{ $cellName }}" value="{{ old( $cellName ) }}" maxlength="200">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </p></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="cargo_number_visible" id="cargo_number_visible" value="{{ old( "cargo_number_visible", 1 ) }}">
                                <input type="hidden" name="date_number_visible" id="date_number_visible" value="{{ old( "date_number_visible" ) > 1 ? old( "date_number_visible" ) : "1" }} ">
                                <button id="courier_announcement_submit_button" type="submit" class="btn btn-primary">{{ __( 'base.courier_announcement_submit_button_text' ) }}</button>


                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/courier_announcement_styles.css') }}">
<script src="{{
    asset('js/courier_announcement_global_variables.js') }}"
    maxCargoNumber="<?php echo $headerData['cargoElementNumber']; ?>"
    maxButtonText="<?php echo __( 'base.courier_announcement_cargo_maximum_cargo_btn' ); ?>"
    maxDateNumber="<?php echo $headerData['dateElementNumber']; ?>"
    maxButtonDateText="<?php echo __( 'base.courier_announcement_cargo_maximum_date_btn' ); ?>"
    deletePictureButtonText="<?php echo __( 'base.courier_announcement_picture_delete_button_text' ); ?>"
    maxPictureNumber="<?php echo $headerData['picturesNumber']; ?>"
    directions="<?php echo htmlentities(json_encode($headerData['directions']), ENT_QUOTES, 'UTF-8'); ?>"
    contactData="<?php echo htmlentities(json_encode($contactData), ENT_QUOTES, 'UTF-8'); ?>"
></script>
<script src="{{ asset('js/courier_announcement_cargo_type_scripts.js') }}"></script>
<script src="{{ asset('js/courier_announcement_date_scripts.js') }}" ></script>
<script src="{{ asset('js/courier_announcement_post_codes_scripts.js') }}"></script>
<script src="{{ asset('js/courier_announcement_pictures_script.js') }}" ></script>

<script src="{{ asset('js/courier_announcement_main_script.js') }}"
    >
</script>


@endsection
