<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoTypeComponent extends Component
{
    public function __construct( $id ) {
        $JsonParserController = app(\App\Http\Controllers\JsonParserController::class);
        $this->currencies_options = $JsonParserController->courierAnnouncementAction()['cargo_currencies'];
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view('components.cargo_type_component', [
            // 'name' => $this->name,
            'id' => $this->id,
            'currencies' => json_encode( $this->currencies_options ),
        ] );
    }

    // private $name;
    private $id;
    private $currencies_options;

}
