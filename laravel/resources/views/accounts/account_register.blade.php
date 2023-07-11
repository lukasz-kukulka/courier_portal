@extends('layouts.app')

@section('add_header')
    <script src="{{ asset('js/accounts_scripts.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/accounts_styles.css') }}">
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $accountData = $JsonParserController->accountAction();
    @endphp
@endsection

@section('content')

<div class="container">
    <form action="{{ route('confirmed_account') }}" method="POST" id="account_register_form">
        <div class="account_options">
        @foreach ( $accountData[ 'accounts_types' ] as $account )
            <div class="rectangle">
                <div class="account_name">{{ $account[ 'name' ] }}</div>
                <input type="hidden" name="account_type_name" value="{{ $account[ 'id' ] }}">
                    @foreach ( $account[ 'description' ] as $description )
                        <div class="acount_description">{{ $description[ 'title' ]}}</div>
                        @if (  $description[ 'status' ] == "can" )
                            <ul class="list_with_can">
                        @else
                            <ul class="list_with_can_not">
                        @endif
                        @foreach ( $description[ 'options' ] as $option )
                            <li>{{ $option }}</li>
                        @endforeach
                        </ul>
                    @endforeach
                    <div class="account_price" >
                        @if ( $account[ 'price' ][ 'discount' ] == true )
                            <span class="account_old_price">{{ $account[ 'price' ][ 'old_price' ] }}</br></span>
                        @endif
                            <span class="account_new_price">{{ $account[ 'price' ][ 'new_price' ] }}</span>
                    </div>
            </div>
        @endforeach
        </div>
    <div class="account_choice_button_container"><button type="submit" class="account_choice_button">{{ $accountData[ 'button_account_choice' ] }}</a></div>
    </form>
</div>
@endsection
