@extends('layouts.app')

@section('add_header')
    {{-- <script src="{{ asset('js/accounts_scripts.js') }}"></script> --}}
    <link rel="stylesheet" href="{{ asset('css/accounts_form_styles.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="confirm_info_account">{{ __('base.change_profile_is_comfirmed') }}</div>
                            <div class="confirm_info_step">{{ __('base.you_account_is_ready_redirected') }}</div><br>
                            <div class="link_to_home_page"><a href="{{ route('main') }}">{{ __('base.back_to_home_page') }}</a></div><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        window.location.href = "{{ route('main') }}";
    }, 5000);
</script>
@endsection
