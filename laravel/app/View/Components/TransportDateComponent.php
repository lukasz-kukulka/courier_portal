<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransportDateComponent extends Component
{
    public function __construct( $id ) {
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $this->directions = $JsonParserController->directionsAction();
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view('components.transport_date_component', [
            'id' => $this->id,
            'directions' => $this->directions,
        ] );
    }

    private $id;
    private $directions;

}