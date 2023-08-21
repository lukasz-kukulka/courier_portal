<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputFormComponent extends Component
{
    public function __construct( $name, $type, $options, $size) {
        //dd($name, $type, $options, $row_size, $x);
        $this->input_name = $name;
        $this->input_type = $type;
        $this->select_options_array = json_decode( $options, true );
        // $this->textarea_size = $row_size;
        //$this->textarea_size = json_decode( $textarea_size, true );
    }

    public function render(): View|Closure|string {
        return view('components.input_form_component', [
            'name' => $this->input_name,
            'type' => $this->input_type,
            'options' => $this->select_options_array,
            'textarea_size' => $this->textarea_size,
        ] );
    }

    private $input_name;
    private $input_type;
    private $select_options_array;
    private $textarea_size;
}
