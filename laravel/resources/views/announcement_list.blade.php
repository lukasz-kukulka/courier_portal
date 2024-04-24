@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/user_announcement_search_filters_style.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('base.announcement_look_for_transport') }}</div>
                <div class="card-body">
                    @php $buttonSectionSize = $hasFilters == true ? 4 : 6; @endphp
                    <div class="filters_container_head row">
                        <div class="filters_container_head_left col-sm-{{ $buttonSectionSize }}">
                            <button type="button" id="courier_announcement_add_filters_button" class="btn btn-warning">{{ __( 'base.courier_announcement_add_filters_button' ) }}</button>
                        </div>
                        @if( $hasFilters == true )
                            <div class="filters_container_head_middle col-sm-{{ $buttonSectionSize }}">
                                <form class="d-inline-block me-2" id="filters_form_show_all" action="{{ route( 'user_announcement.index' ) }}" method="GET">

                                    <button type="submit" id="courier_announcement_clear_filters_show_all_announcement" class="btn btn-success">{{ __( 'base.courier_announcement_filteras_show_all_announcement' ) }}</button>
                                </form>

                            </div>
                        @endif
                        <div class="filters_container_head_right col-sm-{{ $buttonSectionSize }}">
                            <button type="button" id="courier_announcement_hide_filters_button" class="btn btn-info">{{ __( 'base.courier_announcement_hide_filters_button' ) }}</button>
                        </div>


                    </div>

                    <div class="filters_container_body">
                        <form class="d-inline-block me-2" id="filters_form" action="{{ route( 'user_announcement.searchFiltersSummary' ) }}" method="GET">
                            {{-- @csrf --}}
                            {{-- {{ dd( request() ) }} --}}
                            <div class="post_codes_from_container border rounded">
                                <div class="post_codes_from_filter_title border-bottom row">
                                    <div class="post_codes_from_filter_title_left col-sm-6">
                                        <strong>{{ __( 'base.courier_announcement_post_codes_from_title_filter' ) }}</strong>
                                    </div>
                                    <div class="post_codes_from_filter_title_right col-sm-6 text-right">
                                        <div class="post_codes_from_filter_title_right_expand">
                                            {{ __( 'base.courier_announcement_expand_title_filter' )  }} <i class="bi bi-caret-down-fill"></i>
                                        </div>
                                        <div class="post_codes_from_filter_title_right_fold">
                                            {{ __( 'base.courier_announcement_fold_title_filter' )  }} <i class="bi bi-caret-up-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="post_codes_from_filter_body">
                                    <div class="all_direction_from_container">
                                        @foreach( $directions as $key => $value )
                                            <div class="direction_container_{{ $key }} directions_button_container border rounded">
                                                <div class="container_direction_button_from{{ $key }}">
                                                    <button type="button" id="id_direction_button_from_{{ $key }}" class="direction_button_from_{{ $key }} btn btn-dark">
                                                        {{-- <input id="id_directions_checkbox_{{ $key }}" class="form-check-input" type="checkbox" value="1" name="name_directions_checkbox_{{ $key }}"> --}}
                                                        {{ __( $directions[ $key ][ 'print_full_name' ] ) }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="all_postcodes_from_container">
                                        @foreach ( $directions as $key => $value )
                                            <div class="post_codes_from_container_{{ $key }}">

                                                @foreach ( $value[ 'post_codes' ] as $postCode )
                                                    <button type="button" class="post_code_button_from_{{ $key . '_' . $postCode }} btn btn-light">
                                                        <input id="id_post_code_from_checkbox_{{ $key . '_' . $postCode }}" class="form-check-input" type="checkbox" value="1" name="name_post_code_from_checkbox_{{ $key . '_' . $postCode }}">
                                                        {{ $postCode }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="post_codes_from_filter_body_direction" id="id_post_codes_from_filter_body_direction" value="">
                                </div>
                            </div>

                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                            <div class="post_codes_to_container border rounded">
                                <div class="post_codes_to_filter_title border-bottom row">
                                    <div class="post_codes_to_filter_title_left col-sm-6">
                                        <strong>{{ __( 'base.courier_announcement_post_codes_to_title_filter' ) }}</strong>
                                    </div>
                                    <div class="post_codes_to_filter_title_right col-sm-6 text-right">
                                        <div class="post_codes_to_filter_title_right_expand">
                                            {{ __( 'base.courier_announcement_expand_title_filter' )  }} <i class="bi bi-caret-down-fill"></i>
                                        </div>
                                        <div class="post_codes_to_filter_title_right_fold">
                                            {{ __( 'base.courier_announcement_fold_title_filter' )  }} <i class="bi bi-caret-up-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="post_codes_to_filter_body">
                                    <div class="all_direction_to_container">
                                        @foreach( $directions as $key => $value )
                                            <div class="direction_container_{{ $key }} directions_button_container border rounded">
                                                <div class="container_direction_button_{{ $key }}">
                                                    <button type="button" id="id_direction_button_to_{{ $key }}" class="direction_button_to_{{ $key }} btn btn-dark">

                                                        {{ __( $directions[ $key ][ 'print_full_name' ] ) }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="all_postcodes_to_container">
                                        @foreach ( $directions as $key => $value )
                                            <div class="post_codes_to_container_{{ $key }}">
                                                @foreach ( $value[ 'post_codes' ] as $postCode )
                                                    <button type="button" class="post_code_button_from_{{ $key . '_' . $postCode }} btn btn-light">
                                                        <input id="id_post_code_to_checkbox_{{ $key . '_' . $postCode }}" class="form-check-input" type="checkbox" value="1" name="name_post_code_to_checkbox_{{ $key . '_' . $postCode }}">
                                                        {{ $postCode }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="post_codes_to_filter_body_direction" id="id_post_codes_to_filter_body_direction" value="">
                                </div>
                            </div>

                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                            <div class="date_filter_container border rounded">
                                <div class="date_filter_title border-bottom row">
                                    <div class="date_filter_title_left col-sm-6">
                                        <strong>{{ __( 'base.courier_announcement_date_title_filter' ) }}</strong>
                                    </div>
                                    <div class="date_filter_title_right col-sm-6 text-right">
                                        <div class="date_filter_title_right_expand">
                                            {{ __( 'base.courier_announcement_expand_title_filter' )  }} <i class="bi bi-caret-down-fill"></i>
                                        </div>
                                        <div class="date_filter_title_right_fold">
                                            {{ __( 'base.courier_announcement_fold_title_filter' )  }} <i class="bi bi-caret-up-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="date_filter_body">
                                    <div class="all_dates_container text-center">
                                        <input id="id_all_dates_checkbox" class="form-check-input" type="checkbox" checked value="1" name="name_all_dates_checkbox">
                                        {{ __( 'base.courier_announcement_date_show_all_checkbox_title_filter' ) }}
                                    </div>
                                    <div class="custom_date_container row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_days_before">{{ __( 'base.courier_announcement_days_before_field_label' ) }}</label>
                                                <input type="number" class="form-control" id="id_days_before" name="name_days_before" value="0" min="0" max="999">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_date_field">{{ __( 'base.courier_announcement_date_field_label' ) }}</label>
                                                <input type="date" class="form-control" id="id_date_field" name="name_date_field">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_days_after">{{ __( 'base.courier_announcement_days_after_field_label' ) }}</label>
                                                <input type="number" class="form-control" id="id_days_after" name="name_days_after" value="0" min="0" max="999">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}


                            <div class="cargo_type_container border rounded">
                                <div class="cargo_type_filter_title border-bottom row">
                                    <div class="cargo_type_filter_title_left col-sm-6">
                                        <strong>{{ __( 'base.courier_announcement_cargo_type_title_filter' ) }}</strong>
                                    </div>
                                    <div class="cargo_type_filter_title_right col-sm-6 text-right">
                                        <div class="cargo_type_filter_title_right_expand">
                                            {{ __( 'base.courier_announcement_expand_title_filter' )  }} <i class="bi bi-caret-down-fill"></i>
                                        </div>
                                        <div class="cargo_type_filter_title_right_fold">
                                            {{ __( 'base.courier_announcement_fold_title_filter' )  }} <i class="bi bi-caret-up-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="cargo_type_filter_body">
                                    <div class="cargo_type_checkboxes_container text-center">
                                        @foreach ( $cargoType as $cargo )
                                            <input id="id_{{ $cargo[ 'id' ] }}_type_checkbox" class="form-check-input" type="checkbox" checked value="1" name="name_{{ $cargo[ 'id' ] }}_type_checkbox">
                                            {{ __( 'base.show' ) . ' ' . __( 'base.' . $cargo[ 'id' ] . '_accusative_plural' ) }}
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            <div class="submit_filter_button_container">
                                <button type="submit" class="btn btn-primary">{{ __( 'base.courier_announcement_confirm_filters' ) }}</button>
                            </div>


                        </form>
                    </div>

                    <br>


                    @if ( count ( $announcements ) == 0 )
                        <strong><p class="filters_no_results_announcement">{{ __( 'base.user_announcement_filteras_no_results' ) }}</p></strong>
                    @endif
                    @foreach ( $announcements as $announcement )
                        <table class="table table-sm table-light">
                            <thead>
                              <tr class="table-active">
                                <th colspan="2" scope="col">{{ $announcement->title }}</th>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="row"></th>
                                <td>
                                    <strong>{{ __( 'base.delivery_from' ) }}</strong>
                                    {{' ' . __( 'base.direction_print_full_name_genitive_' . $announcement[ 'direction_sending' ] ) . ' ' .
                                    $announcement[ 'post_code_prefix_sending' ] . $announcement[ 'post_code_postfix_sending' ] . ' ' . $announcement[ 'city_sending' ] . ' | ' }}

                                    <strong>{{ __( 'base.delivery_to' ) }}</strong>
                                    {{' ' . __( 'base.direction_print_full_name_genitive_' . $announcement[ 'direction_receiving' ] ) . ' ' .
                                    $announcement[ 'post_code_prefix_receiving' ] . $announcement[ 'post_code_postfix_receiving' ] . ' ' . $announcement[ 'city_receiving' ] }}<br>

                                </td>
                              </tr >
                              <tr>
                                <th scope="row">&nbsp&nbsp</th>

                                <td class="d-flex align-items-center">
                                    <div class="text-start">
                                        <form class="d-inline-block me-2" action="{{ route( 'user_announcement.show', ['user_announcement' => $announcement->id ] ) }}" method="GET" id="user_announcement_single_announcement_show">
                                            <button type="submit" class="btn btn-primary">{{ __( 'base.details_announcement_button' ) }}</button>
                                        </form>
                                        @if ( Auth::user()->id == $announcement->author )
                                            <form class="d-inline-block me-2" action="{{ route('user_announcement.edit', ['user_announcement' => $announcement->id ] ) }}" method="GET" id="user_announcement_single_announcement_edit">
                                                <button type="submit" class="btn btn-success">{{ __( 'base.edit_announcement_button' ) }}</button>
                                            </form>
                                            <form class="d-inline-block me-2" action="{{ route('user_announcement.destroy', ['user_announcement' => $announcement->id ] ) }}" method="POST" id="user_announcement_single_announcement_delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">{{ __( 'base.delete_announcement_button' ) }}</button>
                                            </form>
                                            {{-- @if ( $announcement->priority === null )
                                                <form class="d-inline-block">
                                                    <button type="submit" class="btn btn-warning">{{ __( 'base.upgrade_announcement_button' ) }}</button>
                                                </form>
                                            @endif --}}

                                        @endif
                                    </div>
                                    <div class="small text-end ms-auto">{{ __( 'base.date_look_for_announcement' ) . $announcement->created_at . " | " . __( 'base.date_look_for_announcement_experience' ) . $announcement->experience_date }}</div>
                                </td>

                              </tr>
                            </tbody>
                          </table>
                    @endforeach
                    {{-- <form class="d-inline-block me-2" id="filters_form" action="{{ route( 'user_announcement.searchFiltersSummary' ) }}" method="GET"> --}}
                        {{ $announcements->links() }}
                        @php session( [ 'filtersData' => $filtersData ] );  @endphp
                    {{-- </form> --}}

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/user_announcement_search_filters_scripts.js') }}"></script>

@endsection
