<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeclarationFormController extends Controller
{
    function __construct()
    {
        $this->setDefaultGeneralSettings();
    }

    public function generateCN22DeclarationForm() {
        $form = null;
        $this->maximum_items_in_declaration = $this->general_settings->numbers_of_max_items_cn22;
        $form .= '<div class="CN22wrapper">';
        $form .= '<form id="full_form" action="STRONAXXXXXXXXXXXXXXXXXXXXXX" method="post" target="_blank">';
        $form .= '<input type="hidden" name="form_type" value="cn22">';
        $form .= $this->printCN22DeclarationForm();
        $form .= '<input type="hidden" name="items_num_to_print" value="'.$this->current_showing_items_num.'">';
        $form .= $this->generateSummarySection();
        $form .= $this->generateFormForBusiness();
        $form .= $this->generateSendFormButton();
        $form .= '</form>';
        $form .= '</div>';
        return $form;
    }

    public function generateCN23DeclarationForm() {
        $form = null;
        $this->maximum_items_in_declaration = $this->general_settings->numbers_of_max_items_cn23;
        $form .= '<div class="CN23wrapper">';
        $form .= '<form id="full_form" action="STRONAXXXXXXXXXXXXXXXXXXXXXX" method="post" target="_blank">';
        $form .= '<input type="hidden" name="form_type" value="cn23">';
        $form .= '<form method="post">';
        $form .= $this->printCN23DeclarationForm();
        $form .= '<input type="hidden" name="items_num_to_print" value="'.$this->current_showing_items_num.'">';
        $form .= $this->generateSummarySection();
        $form .= $this->generateFormForBusiness();
        $form .= $this->generateSendFormButton();
        $form .= '</form>';
        $form .= '</div>';
        return $form;
    }

    private function setDefaultGeneralSettings( ) {
        $general_settings_file_name = resource_path('settings'. DIRECTORY_SEPARATOR . 'declaration_form_setting.json');
        $this->general_settings = json_decode( file_get_contents( $general_settings_file_name ) );
        $this->gen_msg = $this->general_settings->messages;
    }

    private function createOneItemInParcel( $required = '' ) {
        $form = null;
        $parcel_item_delete_text = null;
        $this->current_showing_items_num++;
        if ( $this->current_showing_items_num > 1 ) {
            $element_name = "delete_item_button_".$this->current_showing_items_num;
            $parcel_item_delete_text .= '<button onclick="return false;" id="'.$element_name.'" class="'.$element_name.'_class">Usuń przedmiot</button></br>';
        }
        $form .= '<div id="single_item_in_parcel_div_'.$this->current_showing_items_num.'_id" class="single_item_in_parcel_div_'.$this->current_showing_items_num.'_class">';
        $form .= '</br><div class="single_item_parcel_title"><h2 class="title_parcel_item">Przedmiot '.$this->current_showing_items_num.'</h2>'.$parcel_item_delete_text.'</div>';
        $form .= $this->generateFormLabel( 'item_'.$this->current_showing_items_num.'_description', 'Opis przedmiotu' );
        $form .= $this->getSingleTextInput( 'description_'.$this->current_showing_items_num, '[a-zA-Z0-9!@#$%^&*()_ +-=]*', 'Opis przedmiotu', $required, '' );

        $form .= $this->generateFormLabel( 'quantity_input_'.$this->current_showing_items_num, 'Ilość', $required != '' ? true : false );
        $form .= $this->generateSingleNumberInput( 'input_quantity_'.$this->current_showing_items_num, 'sztuk', $required );

        $form .= $this->generateFormLabel( 'weight_input_'.$this->current_showing_items_num, 'Waga', $required != '' ? true : false );
        $form .= $this->generateSingleNumberInput( 'input_weight_'.$this->current_showing_items_num, 'kg', $required );

        $form .= $this->generateFormLabel( 'value_input_'.$this->current_showing_items_num, 'Wartość', $required != '' ? true : false );
        $form .= $this->generateSingleNumberInput( 'input_value_'.$this->current_showing_items_num, '£', $required );

        if ( $this->current_showing_items_num < $this->maximum_items_in_declaration ) {
            $form .= $this->generateAddNewItemButton();
        }

        $form .= '</div>';
        return $form;
    }

    private function generateAddNewItemButton() {
        $form = null;
        $form .= '<button disabled onclick="return false;" id="add_new_item_button_'.$this->current_showing_items_num.
        '" class="add_new_item_button_'.$this->current_showing_items_num.'_class">Dodaj kolejny przedmiot</button></br>';
        $form .= '<div class="add_new_item_button_msg_'.$this->current_showing_items_num.'">
                        <p id="msg_add_new_item_button_'.$this->current_showing_items_num.'">'.$this->gen_msg->add_item_button_conditions.'</p></div>';
        return $form;
    }

    private function generateSendFormButton() {
        return '</br><button type="submit" id="button_send_form" name="create_pdf_button" class="create_pdf_button_class">Generuj</button>';
    }

    private function createAllItemsInParcel( $max_items ) {
        $form = null;
        for ($i = 1; $i <= $max_items; $i++) {
            $form .= $this->createOneItemInParcel();
        }
        return $form;
    }

    private function printCN22DeclarationForm() {
        $form = null;

        $form .= '<h2>Dane nadawcy</h2>';
        $form .= $this->printTextInputs();

        $form .= '<h2>Dane przesyłki</h2>';
        $form .= $this->generateAllCheckBoxes();
        $form .= $this->createAllItemsInParcel( $this->maximum_items_in_declaration );

        return $form;
    }

    private function printCN23DeclarationForm() {
        $form = null;

        $form .= '<h2>Dane nadawcy</h2>';
        $form .= $this->printTextInputs( true, false );

        $form .= '<h2>Dane odbiorcy</h2>';
        $form .= $this->printTextInputs( false, false );
        $form .= '<h2>Dane przesyłki</h2>';
        $form .= $this->generateAllCheckBoxes( );
        $form .= $this->createAllItemsInParcel( $this->maximum_items_in_declaration );
        return $form;
    }

    private function printTextInputs( $is_sender = true, $is_cn22 = true ) {
        $form = null;
        $person = null;
        $prefix_name = "";
        if( $is_sender ) {
            $person = $this->general_settings->sender;
        } else {
            $person = $this->general_settings->receiver;
            $prefix_name = "receiver_";
        }

        $conditions = $this->general_settings->conditions;

        $form .= $this->generateFormLabel( $prefix_name.'name_surname', $person->name );
        $form .= $this->getSingleTextInput( $prefix_name.'name_surname', '[A-Za-z\s]+', $conditions->name_conditions, '', '', 'autofocus' );

        if ( !$is_cn22 ){
            $form .= $this->generateFormLabel( $prefix_name.'business_name', $person->business_name );
            $form .= $this->getSingleTextInput( $prefix_name.'business_name', '[A-Za-z\s]+', $conditions->business_conditions, '', '', '' );
        }

        $form .= $this->generateFormLabel( $prefix_name.'street', $person->street );
        $form .= $this->getSingleTextInput( $prefix_name.'street', '[a-zA-Z0-9\s]+', $conditions->street_conditions, '', '' );

        $form .= $this->generateFormLabel( $prefix_name.'city', $person->city );
        $form .= $this->getSingleTextInput( $prefix_name.'city', '[a-zA-Z\s]+', $conditions->city_condition, '', '' );

        $form .= $this->generateFormLabel( $prefix_name.'country', $person->country);
        $form .= $this->getSingleTextInput( $prefix_name.'country', 'a-zA-Z\s]+', $conditions->country_conditions, '', '' );

        $form .= $this->generateFormLabel( $prefix_name.'post_code', $person->post_code );
        $form .= $this->getSingleTextInput( $prefix_name.'post_code', '[a-zA-Z0-9-]+', $conditions->post_code_conditions, '', '' );
        return $form;
    }

    private function generateAllCheckBoxes() {
        $checkboxes = $this->general_settings->checkboxes_names;
        $check_boxes_data = array(
            array( $checkboxes->gift, "item_checkbox_1"),
            array( $checkboxes->document, "item_checkbox_2"),
            array( $checkboxes->sale, "item_checkbox_3"),
            array( $checkboxes->samples, "item_checkbox_4"),
            array( $checkboxes->returned, "item_checkbox_5"),
            array( $checkboxes->others, "item_checkbox_6")
          );
        $form = null;
        $form .= '<div class="all_declaration_parcel_checkboxes">';
        for ($i = 0; $i < 6; $i++ ) {
            $form .= $this->generateSingeCheckBox( $check_boxes_data[$i][1], $i + 1, $check_boxes_data[$i][0] );
        }

        $form .= '</div>';
        $form .= '<div id="text_others_div"></div>';
        return $form;
    }

    private function generateSingeCheckBox( $name, $value, $checkbox_text ) {
        $form = null;
        $form .= '<div class="'.$name.'_div"><label for="'.$name.'_label_id">';
        $form .= '<input type="checkbox" id="'.$name.'_id" name="'.$name.'" value="'.$value.'">';
        $form .= '<p>'.$checkbox_text.'</p> </label></div>';
        return $form;
    }

    private function getSingleTextInput( $name, $pattern, $title, $required = '', $placeholder = '', $autofocus = '' ) {
        return '</br><input type="text" id="input_'.$name.'_id" name="'.$name.'"
                placeholder="'.$placeholder.'"
                '.$required.'
                '.$autofocus.'
                pattern="'.$pattern.'"
                title="'.$title.'"></br>';
    }

    private function generateFormLabel( $name, $title ) {
        return '<label for="'.$name.'_id">'.$title.'</label>';
    }

    private function generateSingleNumberInput( $name, $postfix = '', $required = '') {
        $form = null;
        $form .= '<div class="'.$name.'_div"><label for="'.$name.'_label_id">';
        $form .= '<input type="number" id="'.$name.'_id" name="'.$name.'" "'.$required.'">';
        $form .= '<span class="number_postfix">'.$postfix.'</span>';
        $form .= '</label></div>';
        return $form;
    }

    private function generateSummarySection() {
        $form = null;
        $info = $this->general_settings->parcel_summary;
        $form .= $this->generateSingeCheckBox( 'summary_checkbox', '', $info->checkbox );
        $form .= '<div class="generate_summary">';
        $form .= $this->generateFormLabel( 'total_parcel_weight', $info->total_parcel_weight );
        $form .= $this->getSingleTextInput( 'total_parcel_weight', '[a-zA-Z0-9]+', $info->total_parcel_weight_conditions, '', '' );
        $form .= $this->generateFormLabel( 'total_parcel_value', $info->total_parcel_value );
        $form .= $this->getSingleTextInput( 'total_parcel_value', '[0-9.]+', $info->total_parcel_value, '', '' );
        $form .= '</div>';
        $form .= '<div class="automatic_summary">';
        $form .= '</div>';
        return $form;
    }

    private function generateFormForBusiness() {
        $form = null;
        $info = $this->general_settings->business_info;
        $form .= $this->generateSingeCheckBox( 'business_checkbox', '', $info->checkbox );

        $form .= '<div class="business_section">';
        $form .= $this->generateFormLabel( 'vat_eori', $info->vat_eori );
        $form .= $this->getSingleTextInput( 'vat_eori', '[a-zA-Z0-9]+', $info->vat_eori_conditions, '', '' );
        $form .= $this->generateFormLabel( 'tariff_num', $info->tariff_num );
        $form .= $this->getSingleTextInput( 'tariff_num', '[0-9.]+', $info->tariff_num_conditions, '', '' );
        $form .= '</div>';
        return $form;
    }

    private $general_settings;
    private $gen_msg;
    private $maximum_items_in_declaration;
    private $current_showing_items_num;
}
