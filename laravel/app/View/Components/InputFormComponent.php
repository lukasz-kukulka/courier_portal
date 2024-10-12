<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\JsonParserController;

class InputFormComponent extends Component
{
    public function __construct( $name, $type, $options = '', $isRequired = 'required', $value = '', $maxLength = 100, $defaultValue = '' ) {
        $this->input_name = $name;
        $this->input_type = $type;
        $this->select_options_array =  $options;
        $this->required = $isRequired;
        $this->value = $value;
        $this->maxLength = $maxLength;
        $this->defaultValue = $defaultValue;
        $json = new JsonParserController;
        $regularExpression = $json->getRegularExpression();
         
        if ( isset( $regularExpression[ $this->input_name ] ) && !empty( $regularExpression[ $this->input_name ] ) ) {
            if( $regularExpression[ $this->input_name ][ 'regex' ] ) {
                $this->regex = $regularExpression[ $this->input_name ][ 'regex' ];
            }
            // if( $this->input_name == 'surname' ) {
            //     dd( $regularExpression, $this->regex );
            //  }
            $this->message = __( 'base.' . $regularExpression[ $this->input_name ][ 'message' ] );
        }
    }

    public function render(): View|Closure|string {
        return view('components.input_form_component', [
            'name' => $this->input_name,
            'type' => $this->input_type,
            'options' => $this->select_options_array,
            'required' => $this->required,
            'value' => $this->value,
            'maxLength' => $this->maxLength,
            'defaultValue' => $this->defaultValue,
            'pattern' => $this->regex,
            'message' => $this->message,
        ] );
    }

    private $input_name;
    private $input_type;
    private $select_options_array;
    private $required;
    private $value;
    private $maxLength;
    private $defaultValue;
    private $regex = '';
    private $message = '';
}