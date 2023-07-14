use App\Http\Form\FormBuilder;

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        {{var_dump( $_POST )}}
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Register') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('create_person_data') }}">
                                @csrf
                                {{-- <input type="hidden" name="group" value="{{ $your_choice }}"> --}}
                                <x-input_form_component name="name" type="text" />
                                <x-input_form_component name="surname" type="text" />
                                <x-input_form_component name="d_o_b" type="date" />
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('base.send') }}
                                        </button>
                                    </div>
                                </div>

                                @if ( $_POST[ 'account_type_input_id' ] == 'courier_pro' )

                                @else
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
