
<div>
    <div class="row mb-3">
        <table class="table table-hover table-sm table-secondary">
            <thead>
                <tr class="table-active">
                    <th scope="col" colspan="12" class="text-center font-weight-bold">{{__( 'base.' . $name . '_base' )}}</th>
                </tr>
              <tr>
                <th></th>
                @foreach ( $params as $key => $param )
                    @if( !str_ends_with($key, '_size') )
                        <th scope="{{ $key }}" class="text-center">{{__( 'base.' . $key . '_base' )}}</th>
                    @endif
                @endforeach
              </tr>
            </thead>
            <tbody>
                @for ( $i = 0; $i < $number; $i++ )
                    <tr>
                        @if ( $name === "human" )
                            @php $number = 0; @endphp
                        @endif
                    <th scope="{{ $name }}row" class="text-center align-middle">{{ $i + 1 }}</th>
                    @foreach ( $params as $key => $param )
                        @if( !str_ends_with($key, '_size') )
                            <td>
                                <div class="d-flex align-items-center">
                                @if ( $param === "number" )
                                    <input name="{{ $name . "_" . $key ."_". $i }}" type="number" id="{{ $name . "_" . $key ."_". $i }}" class="form-control" value="{{ old( $name . "_" . $key ."_". $i, 0  ) }}" min="0" max="99999"/>
                                @elseif ( $param === "text" )
                                    <input name="{{ $name . "_" . $key."_". $i }}" type="text" id="{{ $name . "_" . $key ."_". $i}}" value="{{ old( $name . "_" . $key ."_". $i, 0  ) }}" class="form-control" maxlength="1000"/>
                                @elseif ( $param === "textarea" )
                                    <textarea id="{{ $name . "_" . $key ."_". $i }}" class="form-control" name="{{ $name . "_" . $key ."_". $i }}" rows="{{ $params[ $key . '_size' ] }}" maxlength="10000">{{ old( $name . "_" . $key ."_". $i, 0  ) }}</textarea>
                                @endif
                                    <label class="form-label mt-1" for="{{ $key ."_". $i }}">&nbsp{{__( 'base.' . $key )}}</label>
                                </div>
                            </td>
                        @endif
                    @endforeach
                    </tr>
                @endfor
            </tbody>
          </table>
    </div>
</div>
