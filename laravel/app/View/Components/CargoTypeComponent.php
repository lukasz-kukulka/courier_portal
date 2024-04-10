<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\JsonParserController;

class CargoTypeComponent extends Component
{
    public function __construct( $id ) {
        $jsonParserController = new JsonParserController();
        $this->currencies_options = $jsonParserController->courierAnnouncementAction()[ 'cargo_currencies' ];
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view( 'components.cargo_type_component', [
            'id' => $this->id,
            'currencies' => json_encode( $this->currencies_options ),
        ] );
    }

    private $id;
    private $currencies_options;
}
