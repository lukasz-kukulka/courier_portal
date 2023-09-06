<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoTypeComponent extends Component
{
    public function __construct( $id ) {
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $this->directions = $JsonParserController->directionsAction();
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view('components.cargo_type_component', [
            'id' => $this->id,
            'direction' => json_encode( $this->directions ),
        ] );
    }

    // private $name;
    private $id;
    private $directions;

}

