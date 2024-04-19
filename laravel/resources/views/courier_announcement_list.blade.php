@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/courier_announcement_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/courier_announcement_search_filters_style.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('base.announcement_offer_for_transport') }}</div>
                <div class="card-body">
                    @php $buttonSectionSize = $hasFilters == true ? 4 : 6; @endphp
                    <div class="filters_container_head row">
                        <div class="filters_container_head_left col-sm-{{ $buttonSectionSize }}">
                            <button type="button" id="courier_announcement_add_filters_button" class="btn btn-warning">{{ __( 'base.courier_announcement_add_filters_button' ) }}</button>
                        </div>
                        @if( $hasFilters == true )
                            <div class="filters_container_head_middle col-sm-{{ $buttonSectionSize }}">
                                <form class="d-inline-block me-2" id="filters_form_show_all" action="{{ route( 'courier_announcement.index' ) }}" method="GET">

                                    <button type="submit" id="courier_announcement_clear_filters_show_all_announcement" class="btn btn-success">{{ __( 'base.courier_announcement_filteras_show_all_announcement' ) }}</button>
                                </form>

                            </div>
                        @endif
                        <div class="filters_container_head_right col-sm-{{ $buttonSectionSize }}">
                            <button type="button" id="courier_announcement_hide_filters_button" class="btn btn-info">{{ __( 'base.courier_announcement_hide_filters_button' ) }}</button>
                        </div>


                    </div>
                    <div class="filters_container_body">
                        <form class="d-inline-block me-2" id="filters_form" action="{{ route( 'courier_announcement.searchFiltersSummary' ) }}" method="POST">
                            @csrf
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
                                        @foreach ( $fullDirections as $key => $value )
                                            <div class="post_codes_from_container_{{ $key }}">
                                                @foreach ( $value as $postCode )
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
                                        @foreach ( $fullDirections as $key => $value )
                                            <div class="post_codes_to_container_{{ $key }}">
                                                @foreach ( $value as $postCode )
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
                            <div class="submit_filter_button_container">
                                <button type="submit" class="btn btn-primary">{{ __( 'base.courier_announcement_confirm_filters' ) }}</button>
                            </div>
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}
                            {{-- ////////////////////////////////////////////////////////////////////////////////////////////////// --}}

                        </form>
                    </div>

                    <br>

                    @php $iterator = 0; @endphp
                    @if ( count ( $announcements ) == 0 )
                        <strong><p class="filters_no_results_announcement">{{ __( 'base.courier_announcement_filteras_no_results' ) }}</p></strong>
                    @endif
                    @foreach ( $announcements as $announcement )
                        <table class="table table-sm table-light">
                            <thead>
                              <tr class="table-active">
                                <th colspan="2" scope="col">{{ $announcementTitles[ $iterator++ ] }}</th>

                              </tr>
                            </thead>
                            <tbody>

                              <tr>
                                <th scope="row">&nbsp&nbsp</th>
                                <td class="d-flex align-items-center">
                                    <div class="text-start">
                                        <form class="d-inline-block me-2" action="{{ route('courier_announcement.show', ['courier_announcement' => $announcement->id ] ) }}" method="GET">
                                            <button type="submit" class="btn btn-primary">{{ __( 'base.details_announcement_button' ) }}</button>
                                        </form>
                                        @if ( Auth::user()->id == $announcement->author )
                                            <form class="d-inline-block me-2" action="{{ route('courier_announcement.edit', ['courier_announcement' => $announcement->id ] ) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-success">{{ __( 'base.edit_announcement_button' ) }}</button>
                                            </form>
                                            <form class="d-inline-block me-2" action="{{ route('courier_announcement.destroy', ['courier_announcement' => $announcement->id ] ) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">{{ __( 'base.delete_announcement_button' ) }}</button>
                                            </form>
                                            {{-- @if ( $announcement->priority === null )
                                                <form class="d-inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">{{ __( 'base.upgrade_announcement_button' ) }}</button>
                                                </form>
                                            @endif --}}

                                        @endif
                                    </div>
                                    @php $announcementExperierience = $announcement->experience_date !== null ? $announcement->experience_date : __( 'base.courier_announcement_no_experience_date' ) @endphp
                                    <div class="small text-end ms-auto">{{ __( 'base.date_look_for_announcement' ) . $announcement->created_at . " | " . __( 'base.date_look_for_announcement_experience' ) . $announcementExperierience }}</div>
                                </td>

                              </tr>
                            </tbody>
                          </table>
                    @endforeach
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('js/courier_announcement_main_summary_script.js') }}"></script> --}}
<script src="{{ asset('js/courier_announcement_search_filters_scripts.js') }}"></script>
@endsection
