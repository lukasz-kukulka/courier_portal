@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-header">{{ __('base.no_access_title') }}</div>
                        <div class="card-body">
                            <div class="confirm_info_step">{{ __('base.no_access_content') }}</div><br>
                            <div class="link_to_home_page"><a href="{{ route('account_register') }}">{{ __('base.no_access_change_account_link') }}</a></div><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
