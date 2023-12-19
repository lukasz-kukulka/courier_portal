<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;

use App\Http\Controllers\JsonParserController;

use App\Models\CourierAnnouncement;
use App\Models\CargoTypes;
use App\Models\CourierAnnouncementImages;
use App\Models\CourierTravelDate;
use App\Models\PostCodePl;
use App\Models\PostCodeUk;

class CourierAnnouncementController extends Controller
{
    public function __construct() {
        $this->json = new JsonParserController;

    }
    public function generateCourierAnnouncement( Request $request ) {
        $tempFilePath = $this->generateImagesTempFilesPath();
        $this->saveImagesFilesInTempFolder( $request->file('files'), $tempFilePath );
        return $this->summary( $request );
    }

    public function index() {
        return view('announcement_list', [
            'announcements' => CourierAnnouncement::with( [
                'cargoTypeAnnouncement',
                'imageAnnouncement',
                'dateAnnouncement',
                'postCodesPlAnnouncement',
                'postCodesUkAnnouncement',
            ] )->paginate( $this->json->searchAnnouncementAction()[ 'number_of_search_courier_announcement_in_one_page' ] ),
        ]);
    }

    public function summary( Request $request ) {
        $request->session()->flashInput( $request->input() ); // zamiana post na sesje
        $request->flash();
        $this->validateAllRequestData( $request );
        $countryData = $this->generateDataForDeliveryCountryToSession();
        $company = UserModel::with('company')->find( auth()->user()->id );
        $summaryTitle = $this->generateSummaryAnnouncementTitle( $request, $company );
        $imagesLinks = $this->generateLinksForImages( $request->file('files'), $this->generateImagesTempFilesPath( '/' ) );
        $request->files->replace();

        $headerData = $this->generateCourierAnnouncementSummaryHeader();

        return view( 'courier_announcement_summary' )
                        ->with( 'title', $summaryTitle )
                        ->with( 'countryPostCodesData', $countryData)
                        ->with( 'userAndCompany', $company )
                        ->with( 'imagesLinks', $imagesLinks )
                        ->with( 'headerData', $headerData );
    }

    public function addAnnouncementConfirmation() {
        return view( 'courier_announcement_confirmation_info' );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request ) {
        // $x = ini_set('max_input_vars', 1200);
        // phpinfo();
        // dd( $x );
        $extensions = $this->generateAcceptedFileFormatForCreateBlade();
        $headerData = $this->generateCourierAnnouncementCreateFormHeader();

        //dd( $request->all() );
        return view( 'courier_announcement_create_form' )
            ->with( 'extensions', $extensions )
            ->with( 'headerData', $headerData );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $data = $request->all();
        $courierAnnouncement = new CourierAnnouncement( [
            'name' =>                                   $data[ 'courier_announcement_name' ],
            'description' =>                            $data[ 'additional_description_input' ],
            'experience_date' =>                        $this->getExperienceAnnouncementDate(
                                                            $data[ 'experience_announcement_date_input' ],
                                                            $data[ 'experience_for_premium_date' ]
                                                        ),
        ] );
        $userId = auth()->id();
        $courierAnnouncement->authorUser()->associate( $userId );
        $courierAnnouncement->save();
        $this->storeImages( $courierAnnouncement->id);
        $this->storeCargos( $request, $courierAnnouncement->id);
        $this->storeDates( $request, $courierAnnouncement->id);
        $this->storePostCodesPL( $request, $courierAnnouncement->id);
        $this->storePostCodesUK( $request, $courierAnnouncement->id);

        return $this->addAnnouncementConfirmation();
    }

    private function getExperienceAnnouncementDate( $date, $checkboxDate ) {
        if ( $checkboxDate !== null ) {
            return null;
        }
        return $date;
    }

    private function storeImages( $announcementID ) {
        $filePath = $this->generateImagesFilesPath();
        $tempPath = $this->generateImagesTempFilesPath();
        $folderPath = storage_path( 'app' . DIRECTORY_SEPARATOR . $tempPath );
        $fileNames = collect(File::files($folderPath))->map->getRelativePathname();
        $allNewFilePaths = [];
        foreach( $fileNames as $file ) {
            $oldFilePath = $tempPath . DIRECTORY_SEPARATOR . $file;
            $newFilePath = $filePath . DIRECTORY_SEPARATOR . $file;
            $isSuccessStoreFile = Storage::move( $oldFilePath, $newFilePath );
            $allNewFilePaths[ $file ] = $newFilePath;
            if( !$isSuccessStoreFile ) {
                dd( 'ERROR storeImages() in CourierAnnouncementController' );
            }
        }

        foreach( $allNewFilePaths as $fileKey => $fileLink ) {
            $images = new CourierAnnouncementImages ( [
                'image_name' =>                      $fileKey,
                'image_link' =>                      $fileLink,
            ] );
            $images->announcementId()->associate( $announcementID );
            $images->save();
        }
    }

    private function checkIfLastCargoIsEmpty( Request $request, $lastIndex ) {
        $cargoName = $request->input( 'cargo_name_' . $lastIndex );
        $cargoPrice = $request->input( 'cargo_price_' . $lastIndex );
        $cargoDescription = $request->input( 'cargo_description_' . $lastIndex );
        $cargoCurrency = $request->input( 'select_currency_' . $lastIndex );

        if ( $cargoName === null && $cargoPrice === null && $cargoDescription === null && $cargoCurrency === null ) {
            return true;
        }

        return false;
    }

    private function checkIfLastDateIsEmpty( Request $request, $lastIndex ) {
        $direction = $request->input( 'date_directions_select_' . $lastIndex );
        $date = $request->input( 'date_input_' . $lastIndex );
        $description = $request->input( 'date_description_' . $lastIndex );

        if ( $direction === null && $date === null && $description === null ) {
            return true;
        }

        return false;
    }

    private function storeCargos( Request $request, $announcementID ) {
        $cargoElementsNumber = $request->input( 'cargo_number_visible' );
        if ( $this->checkIfLastCargoIsEmpty( $request, $cargoElementsNumber ) ) {
            $cargoElementsNumber--;
        }

        for ( $i = 1; $i <= $cargoElementsNumber; $i++ ) {

            $cargo = new CargoTypes ( [
                'cargo_name' =>                      $request->input( 'cargo_name_' . $i ),
                'cargo_price' =>                     $request->input( 'cargo_price_' . $i ),
                'cargo_description' =>               $request->input( 'cargo_description_' . $i ),
                'currency' =>                        $request->input( 'select_currency_' . $i ),
            ] );
            $cargo->announcementId()->associate( $announcementID );
            $cargo->save();
        }
    }
    private function storeDates( Request $request, $announcementID ) {
        $dateElementsNumber = $request->input( 'date_number_visible' );
        if ( $this->checkIfLastDateIsEmpty( $request, $dateElementsNumber ) ) {
            $dateElementsNumber--;
        }

        for ( $i = 1; $i <= $dateElementsNumber; $i++ ) {
            $date = new CourierTravelDate ( [
                'direction' =>                      $request->input( 'date_directions_select_' . $i ),
                'date' =>                           $request->input( 'date_input_' . $i ),
                'description' =>                    $request->input( 'date_description_' . $i ),
            ] );
            $date->announcementId()->associate( $announcementID );
            $date->save();
        }
    }

    private function storePostCodesPL( Request $request, $announcementID ) {
        $postCodesArray = [];
        foreach ( $this->json->plPostCodeAction() as $postCode ) {
            if ( $request->input( $postCode ) === $postCode ) {
                $postCodesArray[ $postCode ] = 1;
            } else {
                $postCodesArray[ $postCode ] = 0;
            }
        }

        $postCodesPL = new PostCodePl ( $postCodesArray );
        $postCodesPL->announcementId()->associate( $announcementID );
        $postCodesPL->save();
    }

    private function storePostCodesUK( Request $request, $announcementID ) {
        $postCodesArray = [];
        foreach ( $this->json->ukPostCodeAction() as $postCode ) {
            if ( $request->input( $postCode ) === $postCode ) {
                $postCodesArray[ $postCode ] = 1;
            } else {
                $postCodesArray[ $postCode ] = 0;
            }
        }
        $postCodesUK = new PostCodeUk ( $postCodesArray );
        $postCodesUK->announcementId()->associate( $announcementID );
        $postCodesUK->save();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {

    }

    public function editCreation( Request $request ) {
        $request->session()->flashInput( $request->input() ); // zamiana post na sesje
        $request->flash();

        $extensions = $this->generateAcceptedFileFormatForCreateBlade();
        dd( "EDIT CREATION: ", $request );
        return view( 'courier_announcement_create_form' )
            ->with( 'extensions', $extensions );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    private function generateAcceptedFileFormatForCreateBlade() {
        $extensions = $this->json->courierAnnouncementAction()['accept_format_picture_file'];
        $result = '';
        for ( $i = 0; $i < count( $extensions ); $i++ ) {
            if ( $i === count( $extensions ) - 1 ) {
                $result .= '.' . $extensions[ $i ];
            } else {
                $result .= '.' . $extensions[ $i ] . ', ';
            }
        }
        return $result;
    }

    private function generateAcceptedFileFormatForVerification() {
        $extensions = $this->json->courierAnnouncementAction()['accept_format_picture_file'];
        $result = '';
        for ( $i = 0; $i < count( $extensions ); $i++ ) {
            if ( $i === 0 ) {
                $result .= $extensions[ $i ];
            } else {
                $result .= ',' . $extensions[ $i ];
            }
        }
        return $result;
    }

    private function saveImagesFilesInTempFolder( $files, $savePatch ) {
        if ( $files ) {
            foreach( $files as $file ) {
                // $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                // dd( storage_path(public_path($savePatch ))  );
                // // Zapisz plik w głównym folderze public
                // dd($file->storeAs( public_path($savePatch )  ) );

                Storage::put( $savePatch, $file );
            }
        }

    }

    private function generateImagesFilesPath() {
        $user = Auth::user();

        $folderName = 'personal_images' . DIRECTORY_SEPARATOR . $user->name  . "_" . $user->id . DIRECTORY_SEPARATOR . 'courier_announcement';
        return $folderName;
    }

    private function generateImagesTempFilesPath( $separator = DIRECTORY_SEPARATOR ) {
        $user = Auth::user();

        $folderName =  'public' . $separator . 'temporary_user_images' . $separator . $user->name  . "_" . $user->id . '_temp_images';
        return $folderName;

    }

    // private function createOrCheckFolder( $folderPath, $cleanBefore = false ) {
    //     if( $cleanBefore === true ) {
    //         Storage::deleteDirectory( $folderPath );
    //     }
    //     if ( !File::exists( $folderPath )) {
    //         File::makeDirectory( $folderPath, 0755, true, true);
    //     }
    // }

    private function validateAllRequestData( $request ) {
        $rules = $this->generateAllCargoRules( $request );
        $request->validate( $rules );
    }

    private function generateAllCargoRules( $request ) {
        $rules = [];

        $nameRules[ "courier_announcement_name" ] = 'required';
        $cargoRules = $this->generateCargoValidationRules( $request );
        $dateRules = $this->generateDateValidationRules( $request );
        $postCodesUKRules = $this->generatePostCodeUKValidationRules( $request, $this->json->ukPostCodeAction() );
        $postCodesPLRules = $this->generatePostCodePLValidationRules( $request, $this->json->plPostCodeAction() );
        $experienceDate = $this->generateExperienceDateRules( $request );
        $imagesRules = $this->generateImagesValidationRules( $request->file('files') );
        $rules = array_merge( $nameRules, $cargoRules, $dateRules, $postCodesUKRules, $postCodesPLRules, $experienceDate, $imagesRules );

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

            if( $cargoName === null &&
                $cargoDescription === null &&
                ( $cargoPrice === "0" || $cargoPrice === null ) &&
                ( $selectCurrency == "option_default" || $selectCurrency == null ) ) {
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
            $postCodeName =  $postCode;
            if( $request->input( $postCodeName ) !== "0" && $request->input( $postCodeName ) !== null && $request->input( $postCodeName ) !== "" ) {
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
            $postCodeName =  $postCode;
            if( $request->input( $postCodeName ) !== "0" && $request->input( $postCodeName ) !== null && $request->input( $postCodeName ) !== "" ) {

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

    private function generateExperienceDateRules( $request ) {
        $rules = [];
        // dd($request);
        // dd( "|", $request->input( 'experience_for_premium_date' ), "|", );
        if ( $request->input( 'experience_for_premium_date' ) !== 1 && $request->input( 'experience_for_premium_date' ) !== '1'  ) {
            $rules[ 'experience_announcement_date_input' ] = 'required|date|after:today';
        }
        return $rules;
    }

    private function generateImagesValidationRules( $files ) {
        $rules = [];
        $extensions = $this->generateAcceptedFileFormatForVerification();
        $maxFileSize = $this->generateMaxFileSize();
        $rules[ 'images.*' ] = 'image|mimes:' . $extensions . '|max:' . $maxFileSize;
        return $rules;
    }

    private function generateSummaryAnnouncementTitle( $request, $company ) {
        $courierAnnouncement = $this->json->courierAnnouncementAction();
        $maxCargoInTitle = $courierAnnouncement[ 'max_cargo_names_in_title' ];
        $cargoNumber = $request->input('cargo_number_visible');
        $titleFront = __( 'base.courier_announcement_full_title_summary_front' );
        $titleMid = __( 'base.courier_announcement_full_title_summary_mid' );
        $titleEnd = $cargoNumber > $maxCargoInTitle ? __( 'base.courier_announcement_full_title_summary_end' ) : "";
        $cargoNames = "";
        $companyName = $company->company->company_name;
        for( $i = 1; $i <= min( $cargoNumber, $maxCargoInTitle ); $i++ ) {
            if ( $i > 1 ) {
                $cargoNames .= ", ";
            } else {
                $cargoNames .= " ";
            }
            $cargoNames .= $request->input('cargo_name_' . $i );
        }
        return ( $titleFront . $companyName . $titleMid . $cargoNames . $titleEnd );
    }

    private function generateDataForDeliveryCountryToSession() {
        $fullCountryArray = [];
        $courierAnnouncement = $this->json->courierAnnouncementAction();
        $availableCountries = $courierAnnouncement['available_delivery_country'];

        foreach( $availableCountries as $key => $value ) {
            $singleCountry = [];
            $singleCountry[ 'country_name' ] = $key;
            $singleCountry[ 'translate_text' ] = $value;
            $fullCountryArray[ $key ] = $singleCountry;
        }

        return $fullCountryArray;
    }

    private function generateMaxFileSize() {
        $maxSizeAccess = null;
        $accountType = auth()->user()->account_type;
        $accountsAccess = $this->json->courierAnnouncementAccessElementsAction()[ 'courier_announcement_file_max_size' ];

        if ( in_array( $accountType, $accountsAccess[ 'access_accounts' ] ) ) {
            $maxSizeAccess = $this->json->courierAnnouncementAction()[ 'max_size_single_image_file_premium' ];
        } else if ( in_array( $accountType , $accountsAccess[ 'no_access_accounts' ] ) ) {
            $maxSizeAccess = $this->json->courierAnnouncementAction()[ 'max_size_single_image_file_standard' ];
        } else {
            dd( 'ERROR generateMaxFileSize() in CourierAnnouncementController' );
            // napisać funkcje ktora bedzie wysyłała email w momencie błędu #sema_update
        }

        return $maxSizeAccess ;
    }

    private function generateLinksForImages( $files, $tempPath ) {
        $pathsArray = [];
        $iterator = 1;
        //dd( $tempPath );
        if( $files !== null && count( $files ) > 0 ) {
            foreach( $files as $file ) {
                $pathsArray[ 'image' . $iterator ] = Storage::url( $tempPath . '/' .$file->hashName() );
                $iterator++;
                //array_push( $pathsArray, Storage::url( $tempPath . '/' .$file->hashName() ) );
            }
        }
        //dd( $pathsArray );
        return $pathsArray;
    }

    private $json = null;

    private function generateCourierAnnouncementSummaryHeader() {
        $jsonParserController = app(JsonParserController::class);

        $directions = json_decode($jsonParserController->directionsAction());
        $courierAnnouncement = $jsonParserController->courierAnnouncementAction();
        $postCodesPL = $jsonParserController->plPostCodeAction();
        $postCodesUK = $jsonParserController->ukPostCodeAction();

        return compact(
            'directions',
            'courierAnnouncement',
            'postCodesPL',
            'postCodesUK'
        );
    }


    private function generateCourierAnnouncementCreateFormHeader() {
        $jsonParserController = app(JsonParserController::class);
        $courierAnnouncementData = $jsonParserController->courierAnnouncementAction();

        $cargoElementNumber = $courierAnnouncementData['premium_number_of_type_cargo'];
        $dateElementNumber = $courierAnnouncementData['premium_number_of_type_date'];
        $picturesNumber = $courierAnnouncementData['picture_file_input_limit_premium'];
        $maxFilesPictureElement = $courierAnnouncementData['picture_file_input_limit_premium'];
        $postCodesPL = $jsonParserController->plPostCodeAction();
        $postCodesUK = $jsonParserController->ukPostCodeAction();
        $permDate = $jsonParserController->courierAnnouncementAccessElementsAction()['perm_experience_date_for_premium'];
        $pictureFileFormat = $courierAnnouncementData['accept_format_picture_file'];

        $loginUser = auth()->user();

        return compact(
            'cargoElementNumber',
            'dateElementNumber',
            'picturesNumber',
            'maxFilesPictureElement',
            'postCodesPL',
            'postCodesUK',
            'permDate',
            'pictureFileFormat',
            'loginUser'
        );
    }
}