<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Models\UserModel;

use App\Http\Controllers\JsonParserController;

use App\Models\CourierAnnouncement;
use App\Models\CargoTypes;
use App\Models\CourierAnnouncementImages;
use App\Models\CourierTravelDate;
use App\Models\PostCodePl;
use App\Models\PostCodeUk;
use App\Models\CourierAnnouncementContact;

class CourierAnnouncementController extends Controller
{
    public function __construct() {
        $this->json = new JsonParserController;
    }

    public function generateCourierAnnouncement( Request $request ) {
        dd($request);
        $tempFilePath = $this->generateImagesTempFilesPath();
        $this->generateTempFolderIfDontExist();
        $this->saveImagesFilesInTempFolder( $request->file('files'), $tempFilePath );
        return $this->summary( $request );
    }

    public function index() {
        $query = CourierAnnouncement::with([
            'cargoTypeAnnouncement',
            'imageAnnouncement',
            'dateAnnouncement',
            'postCodesPlAnnouncement',
            'postCodesUkAnnouncement',
        ]);

        $announcementTitles = $this->generateAnnouncementTitlesInList( $query->get() );

        $perPage = $this->json->searchAnnouncementAction()['number_of_search_courier_announcement_in_one_page'];
        $announcements = $query->paginate($perPage);

        $view = view('courier_announcement_list', [
            'announcements' => $announcements,
            'announcementTitles' => $announcementTitles,
        ]);

        return $view;
    }

    public function summary( Request $request ) {
        $request->session()->flashInput( $request->input() ); // zamiana post na sesje
        $request->flash();
        $this->validateAllRequestData( $request );
        $this->generateImagesFolderIfDontExist();
        $countryData = $this->generateDataForDeliveryCountryToSession();
        $company = UserModel::with('company')->find( auth()->user()->id );
        $summaryTitle = $this->generateSummaryAnnouncementTitle( $request, $company );
        $imagesLinks = $this->generateLinksForImages( $request->file('files'), $this->generateImagesTempFilesPath( '/' ) );
        $request->files->replace();
        $headerData = $this->generateCourierAnnouncementSummaryHeader();
        $contactData = $this->generateContactData( $request );

        return view( 'courier_announcement_summary' )
                        ->with( 'title', $summaryTitle )
                        ->with( 'countryPostCodesData', $countryData)
                        ->with( 'userAndCompany', $company )
                        ->with( 'imagesLinks', $imagesLinks )
                        ->with( 'contactData', $contactData )
                        ->with( 'headerData', $headerData );
    }

    public function addAnnouncementConfirmation() {
        return view( 'courier_announcement_confirmation_info' );
    }

    public function create( Request $request ) {
        $company = UserModel::with('company')->find( auth()->user()->id );
        $extensions = $this->generateAcceptedFileFormatForCreateBlade();
        $contactData = $this->generateDataForContact( $company );
        $headerData = $this->generateCourierAnnouncementCreateFormHeader();
        //dd($contactData);
        return view( 'courier_announcement_create_form' )
            ->with( 'extensions', $extensions )
            ->with( 'contactData', $contactData )
            ->with( 'headerData', $headerData );
    }

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
        $this->storeContactData( $request, $courierAnnouncement->id);
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

    private function storeContactData( Request $request, $announcementID ) {
        $contactArray =  $this->generateContactData( $request );

        $date = new CourierAnnouncementContact ( [] );
        foreach ( $contactArray as $key => $value ) {
            $date->{$key} = $value;
        }

        $date->announcementId()->associate( $announcementID );
        $date->save();
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
            $filePathForDataBase = 'storage/' . $this->generateImagesFilesPath( '/', false ) . '/' . $file;
            $allNewFilePaths[ $file ] = $filePathForDataBase;
            if( !$isSuccessStoreFile ) {
                dd( 'ERROR storeImages() in CourierAnnouncementController' );
            }
        }
        //dd( $allNewFilePaths );
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

    public function show( string $id ) {
        $courierAnnouncement = CourierAnnouncement::findOrFail($id);
        //$user = UserModel::findOrFail($courierAnnouncement->author);
        // dd($user);
        $courierAnnouncementCollection = new Collection( [$courierAnnouncement] );
        // dd($courierAnnouncementCollection);
        $announcementTitle = $this->generateAnnouncementTitlesInList( $courierAnnouncementCollection );
        $cargo = $courierAnnouncementCollection[0]->cargoTypeAnnouncement;
        $dates = $courierAnnouncementCollection[0]->dateAnnouncement;
        $directionsPostCodes = $this->getPostCodesAndDirections( $courierAnnouncement );
        $images= $this->getImagesLinks( $courierAnnouncementCollection[0]->imageAnnouncement );
        $readyDates = $this->generateDates( $dates );

        return view( 'courier_announcement_single_show', [] )
                    ->with( 'announcement', $courierAnnouncement->first() )
                    ->with( 'announcementTitle', $announcementTitle[0] )
                    ->with( 'cargo', $cargo )
                    ->with( 'dates', $readyDates )
                    ->with( 'directions', $directionsPostCodes )
                    ->with( 'images', $images );
    }

    private function getPostCodesFromDataBase( $data, $postCodesJson ) {
        $postCodeArray = [];

        if( count($data) === 0 ) {
            return [];
        }

        foreach( $postCodesJson as $postCode ) {
            if( $data[0][$postCode] === 1 ) {
                $postCodeArray[ $postCode ] = $postCode;
            }
        }
        return $postCodeArray;
    }

    private function getPostCodesAndDirections( $courierAnnouncement ) {
        $array = [];
        $courierAnnouncementJson = $this->json->courierAnnouncementAction();
        $availableCountries = $courierAnnouncementJson['available_delivery_country'];
        $postCodesPL = $this->getPostCodesFromDataBase( $courierAnnouncement->postCodesPlAnnouncement,
                                                        $this->json->plPostCodeAction() );
        $postCodesUK = $this->getPostCodesFromDataBase( $courierAnnouncement->postCodesUkAnnouncement,
                                                        $this->json->ukPostCodeAction() );

        if( !empty( $postCodesPL ) ) {
            $array[ $availableCountries[ 'pl' ] ] = $postCodesPL;
        }

        if( !empty( $postCodesUK ) ) {
            $array[ $availableCountries[ 'uk' ] ] = $postCodesUK;
        }

        return $array;

    }

    private function getImagesLinks( $data ) {
        if ( count( $data ) === 0 ) {
            return [];
        }

        $linksArray = [];
        foreach( $data as $link ) {
            $linksArray[] = $link->image_link;
        }

        return $linksArray;
    }

    private function generateDates( $dates ) {
        $array = [];


        foreach( $dates as $date ) {
            if( !isset( $array[ $date->direction ] ) ) {
                $array[ $date->direction ] = '';
            }

            if( $array[ $date->direction ] !== '' ) {
                $array[ $date->direction ] .= ', ';
            }
            $array[ $date->direction ] .= $date->date;

            if( $date->description !== null ) {
                $array[ $date->direction ] .= '( ' . $date->description . ' )';
            }
        }

        return $array;
    }

    public function edit(string $id) { }

    public function editCreation( Request $request ) {
        $request->session()->flashInput( $request->input() ); // zamiana post na sesje
        $request->flash();

        $extensions = $this->generateAcceptedFileFormatForCreateBlade();
        //dd( "EDIT CREATION: ", $request );
        return view( 'courier_announcement_create_form' )
            ->with( 'extensions', $extensions );
    }

    public function update(Request $request, string $id) {
                // $courierAnnouncement = CourierAnnouncement::with([
        //     'cargoTypeAnnouncement',
        //     'imageAnnouncement',
        //     'dateAnnouncement',
        //     'postCodesPlAnnouncement',
        //     'postCodesUkAnnouncement',
        // ])->find( $id );
        // // dd( $courierAnnouncement->cargoTypeAnnouncement );

        // return view( 'courier_announcement_single_show' )
        //                 ->with( '$courierAnnouncement', $courierAnnouncement );
    }

    public function destroy(string $id) {
                // $courierAnnouncement = CourierAnnouncement::with([
        //     'cargoTypeAnnouncement',
        //     'imageAnnouncement',
        //     'dateAnnouncement',
        //     'postCodesPlAnnouncement',
        //     'postCodesUkAnnouncement',
        // ])->find( $id );
        // // dd( $courierAnnouncement->cargoTypeAnnouncement );

        // return view( 'courier_announcement_single_show' )
        //                 ->with( '$courierAnnouncement', $courierAnnouncement );
    }

    private function generateDataForContact( $data ) {
        $contactArray = [];

        $contactArray[ 'name' ] = $data->name;
        $contactArray[ 'surname' ] = $data->surname;
        $contactArray[ 'email' ] = $data->email;
        $contactArray[ 'telephone_number' ] = $data->phone_number;
        $contactArray[ 'additional_telephone_number' ] = '';

        if ( $data->relationLoaded('company') && $data->company !== null ) {
            $contactArray[ 'company' ] = $data->company->company_name;
            $contactArray[ 'street' ] = $data->company->company_address;
            $contactArray[ 'city' ] = $data->company->company_city;
            $contactArray[ 'post_code' ] = $data->company->company_post_code;
            $contactArray[ 'country' ] = $data->company->company_country;
            $contactArray[ 'website' ] = $data->company->company_name;
            if ( $data->phone_number != $data->company->company_phone_number ) {
                $contactArray[ 'additional_telephone_number' ] = $data->company->company_phone_number;
            }
        }

        return $contactArray;
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
                Storage::put( $savePatch, $file );
            }
        }
    }

    private function generateImagesFilesPath( $separator = DIRECTORY_SEPARATOR, $isPublicFolder = true ) {
        $user = Auth::user();
        $userNameFolder = $user->username  . "_" . $user->id;
        $public = $isPublicFolder ? $this->publicFolder . $separator : '';
        return (
            $public .
            $this->personalMainFolderImages .
            $separator .
            $userNameFolder .
            $separator .
            $this->courierAnnouncementCategoryFolder
        );
    }

    private function generateImagesTempFilesPath( $separator = DIRECTORY_SEPARATOR ) {
        $user = Auth::user();
        $personalImagesUserFolder = $user->username  . "_" . $user->id . '_temp_images';
        return (
            $this->publicFolder .
            $separator .
            $this->tempUserFolder .
            $separator .
            $personalImagesUserFolder
        );
    }

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

        if ( $company->company !== null ) {
            $companyName = $company->company->company_name;
        } else {
            $companyName = '';
        }

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

    private function generateAnnouncementTitlesInList( $announcements ) {
        $allTitles = array();
        $courierAnnouncement = $this->json->courierAnnouncementAction();
        $maxCargoInTitle = $courierAnnouncement[ 'max_cargo_names_in_title' ];
        $titleFront = __( 'base.courier_announcement_full_title_summary_front' );
        $titleMid = __( 'base.courier_announcement_full_title_summary_mid' );

        foreach( $announcements as $announcement ) {
            //dd($announcement);
            $cargoNumber = count( $announcement->cargoTypeAnnouncement );
            $titleEnd = $cargoNumber > $maxCargoInTitle ? __( 'base.courier_announcement_full_title_summary_end' ) : "";

            $cargoNames = "";
            $companyName = UserModel::with('company')->find( $announcement->author )->company->company_name;

            for( $i = 0; $i < min( $cargoNumber, $maxCargoInTitle ); $i++ ) {
                if ( $i > 1 ) {
                    $cargoNames .= ", ";
                } else {
                    $cargoNames .= " ";
                }
                $cargoNames .= $announcement->cargoTypeAnnouncement[ $i ]->cargo_name;
            }

            $allTitles[] = ( $titleFront . $companyName . $titleMid . $cargoNames . $titleEnd );
        }
        return $allTitles;
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
        if( $files !== null && count( $files ) > 0 ) {
            foreach( $files as $file ) {
                $pathsArray[ 'image' . $iterator ] = Storage::url( $tempPath . '/' .$file->hashName() );
                $iterator++;
            }
        }
        return $pathsArray;
    }

    private function generateCourierAnnouncementSummaryHeader() {
        $directions = $this->json->directionsAction();
        $postCodesPL = $this->json->plPostCodeAction();
        $postCodesUK = $this->json->ukPostCodeAction();

        return compact(
            'directions',
            'postCodesPL',
            'postCodesUK'
        );
    }


    private function generateCourierAnnouncementCreateFormHeader() {
        $courierAnnouncementData = $this->json->courierAnnouncementAction();

        $cargoElementNumber = $courierAnnouncementData['premium_number_of_type_cargo'];
        // dodac warunek/funkcje zeby sprawdzic czy konto jest premium czy nie i zwrocic poprawny wynik cargoElementNumber #sema_update
        $dateElementNumber = $courierAnnouncementData['premium_number_of_type_date'];
        // dodac warunek/funkcje zeby sprawdzic czy konto jest premium czy nie i zwrocic poprawny wynik dateElementNumber #sema_update
        $picturesNumber = $courierAnnouncementData['picture_file_input_limit_premium'];
        // dodac warunek/funkcje zeby sprawdzic czy konto jest premium czy nie i zwrocic poprawny wynik picturesNumber #sema_update
        $postCodesPL = $this->json->plPostCodeAction();
        $postCodesUK = $this->json->ukPostCodeAction();
        $permDate = $this->json->courierAnnouncementAccessElementsAction()['perm_experience_date_for_premium'];
        $pictureFileFormat = $courierAnnouncementData['accept_format_picture_file'];
        $loginUser = auth()->user();

        return compact(
            'cargoElementNumber',
            'dateElementNumber',
            'picturesNumber',
            'postCodesPL',
            'postCodesUK',
            'permDate',
            'pictureFileFormat',
            'loginUser'
        );
    }

    private function generateTempFolderIfDontExist() {
        $user = Auth::user();
        $personalImagesUserFolder = $user->username  . "_" . $user->id . '_temp_images';
        $tempPatch = storage_path(
            'app' .
            DIRECTORY_SEPARATOR .
            $this->publicFolder .
            DIRECTORY_SEPARATOR .
            $this->tempUserFolder .
            DIRECTORY_SEPARATOR .
            $personalImagesUserFolder
        );

        if ( !File::isDirectory( $tempPatch ) ) {
            File::makeDirectory( $tempPatch, 0755, true );
        }
    }

    private function generateImagesFolderIfDontExist() {
        $user = Auth::user();
        $userNameFolder = $user->username  . "_" . $user->id;
        $tempPatch = storage_path(
            'app' .
            DIRECTORY_SEPARATOR .
            $this->publicFolder .
            DIRECTORY_SEPARATOR .
            $this->personalMainFolderImages .
            DIRECTORY_SEPARATOR .
            $userNameFolder .
            DIRECTORY_SEPARATOR .
            $this->courierAnnouncementCategoryFolder
        );

        if ( !File::isDirectory( $tempPatch ) ) {
            File::makeDirectory( $tempPatch, 0755, true );
        }
    }

    private function generateContactData( $request ) {
        $contactJson = $this->json->courierAnnouncementAction()[ 'contact_form_fields' ];
        $contactArray = [];

        foreach( $contactJson[ 'personal' ] as $field ) {
            $contactArray[ $field ] = $request->input( 'contact_detail_' . $field, null );
        }

        foreach( $contactJson[ 'company' ] as $field ) {
            $contactArray[ $field ] = $request->input( 'contact_detail_' . $field, null );
        }

        return $contactArray;
    }

    private $json = null;
    private $personalMainFolderImages = 'personal_images';
    private $courierAnnouncementCategoryFolder = 'courier_announcement';
    private $publicFolder = 'public';
    private $tempUserFolder = 'temporary_user_images';
}
