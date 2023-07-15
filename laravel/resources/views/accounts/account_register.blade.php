@extends('layouts.app')

@section('add_header')
    <script src="{{ asset('js/accounts_type_scripts.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/accounts_type_styles.css') }}">
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $accountData = $JsonParserController->accountAction();
    @endphp
@endsection

@section('content')

<div class="container">
    <form action="{{ route('confirmed_account') }}" method="POST" id="account_register_form">
        @csrf
        {{ $your_choice = '' }}
        {{-- {{ Auth::user()->name }} --}}
        <div class="account_options">
        <input type="hidden" name="account_type_input_id" value="{{ $your_choice }}">
        @foreach ( $accountData[ 'accounts_types' ] as $account )
            <div class="rectangle"  id="{{ $account[ 'id' ] }}">
                <div class="account_name">{{ $account[ 'name' ] }}</div>

                {{ var_dump($account[ 'id' ] ) }}
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
                            <span class="account_old_price">{{ $account[ 'price' ][ 'old_price' ] }}<br></span>
                        @endif
                            <span class="account_new_price">{{ $account[ 'price' ][ 'new_price' ] }}</span>
                    </div>
            </div>
        @endforeach
        </div>
        <div id="button_errors_container">{{ $accountData[ 'errors' ][ 'account_choice' ]}}</div>
    <div class="account_choice_button_container"><button type="submit" class="account_choice_button">{{ $accountData[ 'button_account_choice' ] }}</button></div>
    </form>
</div>
@endsection
