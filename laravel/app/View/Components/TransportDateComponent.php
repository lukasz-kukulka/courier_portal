<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Http\Controllers\JsonParserController;

class TransportDateComponent extends Component
{
    public function __construct($id) {
        $jsonParserController = app(JsonParserController::class);
        $this->directions = $jsonParserController->getJsonData('directions');
        $this->id = $id;
    }

    public function render(): View|Closure|string {
        return view('components.transport_date_component', [
            'id' => $this->id,
            'directions' => $this->directions,
        ]);
    }

    private $id;
    private $directions;
}