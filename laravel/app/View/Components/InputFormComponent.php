<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputFormComponent extends Component
{
    public function __construct( $name, $type ) {
        $this->input_name = $name;
        $this->input_type = $type;
    }

    public function render(): View|Closure|string {
        return view('components.input_form_component', [
            'name' => $this->input_name,
            'type' => $this->input_type
        ] );
    }

    private $input_name;
    private $input_type;
}
