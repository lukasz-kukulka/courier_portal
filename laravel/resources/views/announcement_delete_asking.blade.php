@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('base.announcement_delete_asking_title') }}</div>
                        <div class="card-body text-center">
                            <div class="confirm_info_step">{{ __('base.announcement_delete_asking_question') }}</div><br>
                            {{-- {{ dd( request() )}} --}}
                            <div class="row justify-content-between">
                                <div class="button_delete_confirm col-3">
                                    <button type="button" class="btn btn-primary" onclick="window.location='{{ request()->header('referer') }}'">{{ __( 'base.cancel' ) }}</button>
                                </div>
                                <form class="button_delete_cancel col-3" action="{{ route('announcement_confirm_destroy', [ 'id' => $announcementId ] ) }}" method="POST" id="user_announcement_delete_confirm_form">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">{{ __( 'base.delete_announcement_button' ) }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
