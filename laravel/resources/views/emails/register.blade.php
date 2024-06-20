@component('mail::message')
# {{ __('emails.verify_greeting') }}

{{ __('emails.verify_line') }}

@component('mail::button', ['url' => $url])
{{ __('emails.verify_action') }}
@endcomponent

{{ __('emails.verify_salutation') }}<br>
{{ config('app.name') }}
@endcomponent

