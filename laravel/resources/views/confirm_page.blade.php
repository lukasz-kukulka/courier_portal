@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center">
                        @if( isset( $data ) )
                            @foreach ($data as $key => $value )
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                        @endif
                        <div class="card-header">{{ __('base.question_title') }}</div>
                        <div class="card-body">
                            <div class="question_{{ $id }}">{{ $question }}</div><br>
                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="button" class="btn btn-danger">{{ __('base.no') }}</button>
                                    </div>
                                    <div class="col text-left">
                                        <form method="POST" action="{{ route('login') }}">
                                            <button type="button" class="btn btn-success">{{ __('base.yes') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
