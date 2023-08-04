<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoDetailsComponent extends Component
{
    public function __construct( $name = '', int $number = 0, $params = '' ) {
        $this->param_table_name = $name;
        $this->numbers = $number;
        $this->params_elements = json_decode($params, true);
    }

    public function render(): View|Closure|string {
        return view('components.cargo_details_component', [
            'name' => $this->param_table_name,
            'number' => $this->numbers,
            'params' => $this->params_elements,
        ] );
    }

    private $param_table_name;
    private $params_elements;
    private $numbers;
}
