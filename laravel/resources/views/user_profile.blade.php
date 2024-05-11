@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center">
                        <div class="card-header">{{ __('base.user_profile_title') }}</div>
                        <div class="card-body">
                            {{-- {{dd(Auth::user()->id)}} --}}
                            <form method="POST" action="{{ route('userDestroy', Auth::user()->id ) }}">
                                @csrf
                                @method('DELETE')
                                <div class="row">
                                    <div class="col d-flex align-items-left">
                                        <button type="submit" class="btn btn-success" name="response" value="yes">{{ __('base.user_account_delete_button') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
