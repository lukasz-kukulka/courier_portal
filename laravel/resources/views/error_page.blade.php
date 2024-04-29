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
                            <div class="question_{{ $id }}">{{ $question }}</div><br>
                            <div class="card-body">
                                <h5>{{ __('Czy lubisz programować?') }}</h5>
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="button" class="btn btn-danger">{{ __('Nie') }}</button>
                                    </div>
                                    <div class="col text-left">
                                        <button type="button" class="btn btn-success">{{ __('Tak') }}</button>
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
