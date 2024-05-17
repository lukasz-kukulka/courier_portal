@extends('layouts.app')

@section('add_header')
    <link rel="stylesheet" href="{{ asset('css/declaration_styles.css') }}">
    @php
        $declarationFormController = app(\App\Http\Controllers\DeclarationFormController::class);
    @endphp
@endsection

@section('content')
<style>
    .declaration_buttons_choice_wrapper form {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

.declaration_buttons_choice_wrapper form button {
    margin-top: 10px;
    font-size: 18px;
  }

.all_declaration_parcel_checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    grid-gap: 0px;
  }

.all_declaration_parcel_checkboxes p {
    margin: -23px 0px 0px 18px;
  }

.gift, .documents, .selling_goods, .commercial_sample, .returned_goods, .others {
    height: 4fr;
    width: 1fr;
  }

.gift_id, .documents_id, .selling_goods_id, .commercial_sample_id, .returned_goods_id, .others_id {
  margin: 0px 0px 0px 2px;
  }

.number_postfix {
    display: inline-block;
    margin-left: 5px;
  }

[class^="single_item_in_parcel_div_"]:not( .single_item_in_parcel_div_1_class ) {
  display: none;
}

.single_item_in_parcel_div_1 {
  display: inline-block;;
}

.title_parcel_item {
  display: inline;
}

.single_item_parcel_title {
  display: block;
}

[class^="delete_item_button_"] {
  vertical-align: 6px;
  margin: 0px 0px 0px 10px;
  padding: 0px 0px 0px 0px;
  width: 130px;
  height: 24px;
}

[class^="delete_item_button_"]:active {
  vertical-align: 3px;
  margin: 0px 0px 0px 10px;
  padding: 0px 0px 0px 0px;
  line-height: 0px;
  width: 130px;
  height: 24px;
}

button[class^="add_new_item_button_"][disabled] {
  background: initial;
  background-color: rgb(83, 83, 83);
  pointer-events: none;
}

.class_div_message_error_checkboxes_item {
  height: 0px;
}

[class^="add_new_item_button_msg_"] {
  height: 0px;
  color: red;
  font-size: 14px;
  margin-top: -0px;
}

.create_pdf_button_class {
  float: right;
}

.business_checkbox_div {
  display: grid;
  grid-gap: 0px;
  margin: 40px 0px 0px 0px;
}

.business_checkbox_div p {
  margin: -25px 0px 0px 18px;
}

.business_section {
  display: none;
}

.summary_checkbox_div {
  display: grid;
  grid-gap: 0px;
  margin: 40px 0px 0px 0px;
}

.summary_checkbox_div p {
  margin: -25px 0px 0px 18px;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Formularz do deklaracji CN22') }}</div>

                <div class="card-body">
                    {!! $declarationFormController->generateCN22DeclarationForm() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/declaration_scripts.js') }}"></script>
@endsection
