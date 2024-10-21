<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomFormComponent extends Component
{
    public function __construct( $name ) {
       
    }

    public function render(): View|Closure|string {
        return view('components.cargo_details_component', [
            'name' => $this->param_table_name,
            'number' => $this->numbers,
            'params' => $this->params_elements,
        ] );
    }

    private $name;
    
}
