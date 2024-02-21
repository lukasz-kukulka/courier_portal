
<tr class="date_component_{{ $id }} align-middle h-100">
    <th scope="row" class="h3">{{ $id }}</th>
    <td>
        <div class="from_directions_container">
            <input type="hidden" name="from_date_directions_select_{{ $id }}" value="{{ old("from_date_directions_select_" . $id) !== null ? old("from_date_directions_select_" . $id) : "default_direction" }}">
            <select id="from_date_directions_select_{{ $id }}" name="from_date_directions_select_{{ $id }}" class="form-group form-control @error( "from_date_directions_select_" . $id ) is-invalid @enderror">
                {{-- <option value="default_direction" disabled selected>{{ __('base.default_date_direction_option')}}</option> --}}
                <option value="{{ old("from_date_directions_select_" . $id) != "default_direction" ? old("from_date_directions_select_" . $id) : "default_direction" }}" disabled selected>{{ old("from_date_directions_select_" . $id) != "default_direction" && old("from_date_directions_select_" . $id) != null && old("from_date_directions_select_" . $id) != "" ? $directions[ old("from_date_directions_select_" . $id) ]['print_name'] : __('base.default_date_direction_option') }}</option>

                @foreach ( $directions as $direction )
                    <option value="{{ $direction[ 'name' ] }}">{{ $direction['print_name'] }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <div class="to_directions_container">
            <input type="hidden" name="to_date_directions_select_{{ $id }}" value="{{ old("to_date_directions_select_" . $id) !== null ? old("to_date_directions_select_" . $id) : "default_direction" }}">
            <select id="to_date_directions_select_{{ $id }}" name="to_date_directions_select_{{ $id }}" class="form-group form-control @error( "to_date_directions_select_" . $id ) is-invalid @enderror">
                {{-- <option value="default_direction" disabled selected>{{ __('base.default_date_direction_option')}}</option> --}}
                <option value="{{ old("to_date_directions_select_" . $id) != "default_direction" ? old("to_date_directions_select_" . $id) : "default_direction" }}" disabled selected>{{ old("to_date_directions_select_" . $id) != "default_direction" && old("to_date_directions_select_" . $id) != null && old("to_date_directions_select_" . $id) != "" ? $directions[ old("to_date_directions_select_" . $id) ]['print_name'] : __('base.default_date_direction_option') }}</option>

                @foreach ( $directions as $direction )
                    <option value="{{ $direction[ 'name' ] }}">{{ $direction['print_name'] }}</option>
                @endforeach
            </select>
        </div>
    </td>
    <td>
        <input type="date" class="form-control @error( "date_input_" . $id ) is-invalid @enderror" id="date_input_{{ $id }}" name="date_input_{{ $id }}" value="{{ old( "date_input_" . $id  ) }}">
    </td>

    <td>
        <textarea id="date_description_{{ $id }}" class="form-control" name="date_description_{{ $id }}" autocomplete="date_description_{{ $id }}" rows="1">{{ old( "date_description_" . $id ) }}</textarea>

    </td>

    <td>
        <div class="action_date_container_button_{{ $id }}">
            <a href="#" data-toggle="tooltip" title="{{ __( 'base.courier_announcement_date_action_delete_info' ) }}">
                <button class="date_delete_btn_{{ $id }} btn" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="white" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                    </svg>
                </button>
            </a>
        </div>
        @if ( $id == 1 )
            <div class="action_date_container_info">
                <p>{{ __( 'base.courier_announcement_date_min_one_type' ) }}</p>
            </div>
        @endif
    </td>
</tr>

