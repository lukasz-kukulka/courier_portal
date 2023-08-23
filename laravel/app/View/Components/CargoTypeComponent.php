<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoTypeComponent extends Component
{
    public function __construct( $id ) {
        // $this->name = $name;
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view('components.cargo_type_component', [
            // 'name' => $this->name,
            'id' => $this->id,
        ] );
    }

    // private $name;
    private $id;

}
