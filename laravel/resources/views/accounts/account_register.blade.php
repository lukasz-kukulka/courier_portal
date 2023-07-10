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
    @foreach ( $accountData as $account )
        <div class="rectangle">
            <div class="account_name">{{ $account[ 'name' ] }}</div>
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


{{-- <div class="container">
    <div class="row justify-content-center">
        <table class="table table-hover table-dark table-hover-clickable table_account">
            <thead>
              <tr>
                <th scope="col" class="col-2" ></th>
                <th scope="col" class="col-8" >Opis konta</th>
                <th scope="col" class="col-1">Cena</th>
              </tr>
            </thead>
            <tbody>

                @foreach ( $accountData as $account )
                <tr class="account_row">
                <th class="account_name"> {{ $account[ 'name' ] }} </th>
                    <td>
                    <table class="table table-bordered table-dark">
                        @foreach ( $account[ 'description' ] as $description )
                        <thead>
                            <tr>
                                <th>{{ $description[ 'title' ]}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                @if (  $description[ 'status' ] == "can" )
                                    <ul class="list-with-can">
                                @else
                                    <ul class="list-with-can_not">
                                @endif
                                @foreach ( $description[ 'options' ] as $option )
                                    <li>{{ $option }}</li>
                                @endforeach
                                </ul>
                            </td>
                        </tbody>
                        @endforeach
                    </table>
                    </td>
                    <td class="account-price" >
                        @if ( $account[ 'price' ][ 'discount' ] == true )
                            <span class="account-old-price">{{ $account[ 'price' ][ 'old_price' ] }}</br></span>
                        @endif
                            <span class="account-new-price">{{ $account[ 'price' ][ 'new_price' ] }}</span>

                    </td>
                </tr>
                @endforeach

            </tbody>
          </table>
    </div>
</div> --}}
@endsection
