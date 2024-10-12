@extends('layouts.app')

@section('add_header')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header">{{ __('base.donate_title') }}</div>
                <div class="card-body">  
                    <div id="donate-button-container">
                        <div id="donate-button"></div>
                        <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
                        <script>
                        PayPal.Donation.Button({
                        env:'production',
                        hosted_button_id:'SCCUQSG6LQY7C',
                        image: {
                        src:'https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif',
                        alt:'Donate with PayPal button',
                        title:'PayPal - The safer, easier way to pay online!',
                        }
                        }).render('#donate-button');
                        </script>
                        </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
