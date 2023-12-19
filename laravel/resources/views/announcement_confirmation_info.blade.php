@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.announcement_confirm_info') }}</div>
                        <div class="card-body">
                            <div class="confirm_info_step">{{ __('base.announcement_confirm_added') }}</div><br>
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
