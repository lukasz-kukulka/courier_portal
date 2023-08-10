@if ( $item['type'] == "standard" )
    <li class="nav-item">
        <a class="nav-link" href="{{ route( $item['route_name'] ) }}">{{ __( 'base.' . $item['name'] ) }}</a>
    </li>
    @elseif ( $item['type'] == "dropdown" )
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __( 'base.' . $item['name'] )  }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach ( $item[ 'dropdown_elements' ] as $subelement )
                <a class="dropdown-item" href="{{ route( $subelement['route_name'] ) }}">{{ __( 'base.' . $subelement['name'] ) }}</a>
                <div class="dropdown-divider"></div>
            @endforeach
            </div>
        </li>
@endif
