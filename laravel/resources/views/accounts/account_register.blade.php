@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/declaration_scripts.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/accounts_styles.css') }}">
    @php
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $accountData = $JsonParserController->accountAction();
    @endphp
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <table class="table table-hover table-dark">
            <thead>
              <tr>
                <th scope="col" class="col-2" ></th>
                <th scope="col" class="col-8" >Opis konta</th>
                <th scope="col" class="col-1">Cena</th>
              </tr>
            </thead>
            <tbody>

                @foreach ( $accountData as $account )
                <tr>
                <th class="account-name"> {{ $account[ 'name' ] }} </th>
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
                                <ul>
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
                {{-- <th class="account-name" scope="row" class="account-row"><h1>Standard</h1></th>
                <td>
                    <table class="table table-bordered table-dark">
                        <thead>
                            <tr>
                                <th>Co bedziesz mógł robić</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <ul>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                </ul>
                            </td>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-dark">
                        <thead>
                            <tr>
                                <th>Czego nie będziesz mógł robić</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <ul>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                    <li>xxxxx</li>
                                </ul>
                            </td>
                        </tbody>
                    </table>
                </td>
                <td>

                </td>

              </tr>
              <tr>
                <th  class="account-name" scope="row" class="account-row"><h1>Kurier</h1></th>
                <td>kurier opis</td>
                <td>kurier cena</td>

              </tr>
              <tr>
                <th  class="account-name" scope="row" class="account-row"><h1>Kurier PRO</h1></th>
                <td>pro opis</td>
                <td> pro cena</td>--}}

            </tbody>
          </table>
    </div>
</div>
@endsection
