<div>
    <div class="row mb-1">
        <label for="{{ $name }}" class="col-md-4 col-form-label text-md-end align-self-center">{{ __('base.'.$name) }}</label>
        @php $default_data = $value; @endphp
        <div class="col-md-8">
            @if ( $type === 'textarea' )
                <textarea id="{{ $name }}" class="form-control @error( $name ) is-invalid @enderror" name="{{ $name }}" {{ $required }} autocomplete="{{ $name }}" rows="1" maxlength="{{ $maxLength }}">{{ old( $name ) }}</textarea>
            @elseif ( $type === 'select' )
                <select id="{{ $name }}" class="form-control @error( $name ) is-invalid @enderror" name="{{ $name }}" {{ $required }} autocomplete="{{ $name }}">
                    <option value="" disabled selected>{{ __('base.default_direction_option')}}</option>
                    @foreach ( $options as $direction )
                        <option value="{{ $direction[ 'name' ] }}">{{ $direction[ 'print_name' ] }}</option>
                    @endforeach
                </select>
            @elseif ( $type === 'direction')
                <div class="row mb-2 {{ $name }}">
                    <div class="row pl-3 alert_direction_container">
                        @if( Session::has('allDirectionData') && session('allDirectionData') === false )
                            <p class="alert alert_direction alert-danger pl-3" role="alert">{{ __( 'base.announcement_is_wrong_direction_message' ) }}</p>
                            @php  Session::forget('allDirectionData'); @endphp
                        @endif
                    </div>
                    <div class="col-md-2">
                        <div class="text-center"><b><label for="country_select_{{ $name }}" class="col-form-label">{{ __('base.default_direction_country') }}</label></b></div>
                        <select id="country_select_{{ $name }}" class="form-control country_select_{{ $name }} @error('country_select_' . $name ) is-invalid @enderror" name="country_select_{{ $name }}" {{ $required }} autocomplete="country_select_{{ $name }}">
                            <option value="" disabled {{ old( "country_select_" . $name ) ? '' : 'selected' }}>{{ __('base.default_direction_country') }}</option>
                            @foreach ( $options as $direction )
                                <option value="{{ $direction['name'] }}" {{ old( "country_select_" . $name ) == $direction['name'] ? 'selected' : '' }}>
                                    <span>{!! html_entity_decode( $direction['unicode'] ) !!}</span>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        @foreach ( $options as $direction )
                            <div class="direction_container_{{ $direction['name'] . "_" . $name }} {{ $direction['name'] }}">
                                <div class="text-center"><b><label for="prefix_select_{{ $direction['name'] }}" class="col-form-label">{{ __('base.default_direction_prefix') }}</label></b>
                                    <div class="tooltip">
                                        <i class="bi bi-question-diamond-fill"></i>
                                        <span class="tooltiptext">{{ __( 'base.prefix_explain_notes') }}</span>
                                    </div>
                                </div>
                                <select id="prefix_select_{{ $direction['name'] . "_" . $name }}" class="prefix_select_{{ $direction['name'] . "_" . $name }} form-control @error('prefix_select_' . $direction['name'] . "_" . $name ) is-invalid @enderror" name="prefix_select_{{ $direction['name'] . "_" . $name }}" autocomplete="prefix_select_{{ $direction['name'] . "_" . $name }}">
                                    <option value="" disabled {{ old( 'prefix_select_' .  $direction['name'] . "_" . $name ) ? '' : 'selected' }}>{{ __('base.default_direction_prefix') }}</option>
                                    @if(isset($direction['post_codes']))
                                        @foreach ( $direction[ 'post_codes' ] as $postCode )
                                            <option value="{{ $postCode }}" {{ old( 'prefix_select_' . $direction['name'] . "_" . $name ) == $postCode ? 'selected' : '' }}>
                                                {{ $postCode }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-3 postfix_container_{{ $name }}">
                        <div class="text-center"><b><label for="postfix_select_{{ $name }}" class="col-form-label">{{ __('base.default_direction_postfix') }}</label></b>
                            <div class="tooltip">
                                <i class="bi bi-question-diamond-fill"></i>
                                <span class="tooltiptext">{{ __( 'base.postfix_explain_notes' ) }}</span>
                            </div>
                        </div>
                        <input id="postfix_select_{{ $name }}" type="text" class="form-control @error( "postfix_select_" . $name ) is-invalid @enderror" name="postfix_select_{{ $name }}" value="{{ old( "postfix_select_" . $name ) }}" autocomplete="postfix_select_{{ $name }}" maxlength="6">
                    </div>
                    <div class="col-md-4 direction_city_container_{{ $name }}">
                        <div class="text-center"><b><label for="direction_city_{{ $name }}" class="col-form-label">{{ __('base.user_announcement_direction_city') }}</label></b></div>
                        <input id="direction_city_{{ $name }}" type="text" class="form-control @error( "direction_city_" . $name ) is-invalid @enderror" name="direction_city_{{ $name }}" value="{{ old( "direction_city_" . $name ) }}" autocomplete="direction_city_{{ $name }}" maxlength="80">
                    </div>
                </div>
            @else
                <input id="{{ $name }}" type="{{ $type }}" class="form-control @error( $name ) is-invalid @enderror" name="{{ $name }}" value="{{ old( $name, $default_data ) }}" {{ $required }} autocomplete="{{ $name }}" maxlength="{{ $maxLength }}">
            @endif
            @error( $name )
                <span class="invalid-feedback" role="alert">
                    <strong> {{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
