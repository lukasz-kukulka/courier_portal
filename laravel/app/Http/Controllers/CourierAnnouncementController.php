<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\JsonParserController;

class CourierAnnouncementController extends Controller
{
    public function generateCourierAnnouncement( Request $request ) {
        //dd( $request );
        $this->validateAllRequestData( $request );
        $this->createOrCheckFolder();
        //dd( $request->all() );
    }

    public function index() {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view( 'courier_announcement_create_form' );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function createOrCheckFolder() {
        $user = Auth::user();
        $folderName = 'personal_images' . DIRECTORY_SEPARATOR . $user->name  . "_" . $user->id . DIRECTORY_SEPARATOR . 'courier_announcement';
        $folderPath = public_path( $folderName );

        if (!File::exists( $folderPath )) {
            File::makeDirectory( $folderPath, 0755, true, true);
        }
    }

    private function validateAllRequestData( $request ) {
        $rules = $this->generateAllCargoRules( $request );
        //dd($rules);
        $request->validate( $rules );
    }

    private function generateAllCargoRules( $request ) {
        $rules = [];
        $json = new JsonParserController;

        $nameRules[ "courier_announcement_name" ] = 'required';
        $cargoRules = $this->generateCargoValidationRules( $request );
        $dateRules = $this->generateDateValidationRules( $request );
        $postCodesUKRules = $this->generatePostCodeUKValidationRules( $request, $json->ukPostCodeAction() );
        $postCodesPLRules = $this->generatePostCodePLValidationRules( $request, $json->plPostCodeAction() );
        $imagesRules = $this->generateImagesValidationRules( $request );
        $rules = array_merge( $nameRules, $cargoRules, $dateRules, $postCodesUKRules, $postCodesPLRules, $imagesRules );

        return $rules;
    }

    private function generateCargoValidationRules( $request ) {
        $rules = [];

        $rules[ "cargo_name_1" ] = 'required';
        $rules[ "cargo_description_1" ] = 'required';
        $rules[ "cargo_price_1" ] = 'required|numeric|min:1';
        $rules[ "select_currency_1" ] = 'required|not_in:option_default';

        for( $i = 2; $i < 10000; $i++ ) {
            $cargoName = $request->input(  'cargo_name_' . $i );
            $cargoDescription = $request->input(  'cargo_description_' . $i );
            $cargoPrice = $request->input(  'cargo_price_' . $i );
            $selectCurrency = $request->input(  'select_currency_' . $i );

            if( $cargoName === null && $cargoDescription === null && $cargoPrice === "0" && $selectCurrency == "option_default" ) {
                break;
            } else {
                $rules[ "cargo_name_" . $i ] = 'required';
                $rules[ "cargo_description_" . $i ] = 'required';
                $rules[ "cargo_price_" . $i ] = 'required|numeric|min:1';
                $rules[ "select_currency_" . $i ] = 'required|not_in:option_default';
            }
        }
        return $rules;
    }

    private function generateDateValidationRules( $request ) {
        $rules = [];

        $rules[ "date_directions_select_1" ] = 'required|not_in:default_direction';
        $rules[ "date_input_1" ] = 'required|date|after:today';

        for( $i = 2; $i < 10000; $i++ ) {
            $dateDirection = $request->input(  'date_directions_select_' . $i );
            $date = $request->input(  'date_input_' . $i );

            if( ( $dateDirection === null || $dateDirection === "default_direction" ) && $date === null ) {
                break;
            } else {
                $rules[ "date_directions_select_" . $i ] = 'required|not_in:default_direction';
                $rules[ "date_input_" . $i ] = 'required|date|after:today';
            }
        }
        return $rules;
    }

    private function generatePostCodePLValidationRules( $request, $json ) {
        $rules = [];
        $postCodePLRequired = true;
        foreach( $json as $postCode ) {
            $postCodeName = "name_post_code_checkbox_pl_" . $postCode;
            if( $request->input( $postCodeName ) !== "0" ) {
                $postCodePLRequired = false;

                break;
            } else {
                $rules[ $postCodeName ] = 'required|not_in:0';
            }
        }

        if( $postCodePLRequired === true ) {
            return $rules;
        }

        return [];
    }

    private function generatePostCodeUKValidationRules( $request, $json ) {
        $rules = [];
        $postCodeUKRequired = true;
        foreach( $json as $postCode ) {
            $postCodeName = "name_post_code_checkbox_uk_" . $postCode;
            if( $request->input( $postCodeName ) !== "0" ) {
                $postCodeUKRequired = false;

                break;
            } else {
                $rules[ $postCodeName ] = 'required|not_in:0';
            }
        }

        if( $postCodeUKRequired === true ) {
            return $rules;
        }

        return [];
    }

    private function generateImagesValidationRules( $request ) {
        $rules = [];

        return $rules;
    }
}
