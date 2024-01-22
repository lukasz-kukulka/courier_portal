<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputFormComponent extends Component
{
    public function __construct( $name, $type, $options = '', $isRequired = 'required', $value = '' ) {
        $this->input_name = $name;
        $this->input_type = $type;
        $this->select_options_array =  $options;
        $this->required = $isRequired;
        $this->value = $value;
    }

    public function render(): View|Closure|string {
        return view('components.input_form_component', [
            'name' => $this->input_name,
            'type' => $this->input_type,
            'options' => $this->select_options_array,
            'required' => $this->required,
            'value' => $this->value,
        ] );
    }

    private $input_name;
    private $input_type;
    private $select_options_array;
    private $required;
    private $value;
}