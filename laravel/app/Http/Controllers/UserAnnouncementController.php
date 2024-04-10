<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\JsonParserController;
use Illuminate\Database\Eloquent\Collection;
// use App\Helpers\translate_helpers;
use App\Models\UserAnnouncement;
use App\Models\AnimalAnnouncement;
use App\Models\HumanAnnouncement;
use App\Models\OtherAnnouncement;
use App\Models\PalletAnnouncement;
use App\Models\ParcelAnnouncement;
use App\Models\UserAnnouncementArchive;
use App\Models\AnimalAnnouncementArchive;
use App\Models\HumanAnnouncementArchive;
use App\Models\OtherAnnouncementArchive;
use App\Models\PalletAnnouncementArchive;
use App\Models\ParcelAnnouncementArchive;
use Illuminate\Support\Facades\Auth;
use DateTime;

class UserAnnouncementController extends Controller
{
    private $jsonParserController;
    private $announcement_json;
    public function __construct(JsonParserController $jsonParserController) {
        $this->jsonParserController = $jsonParserController;
        $this->announcement_json = $this->jsonParserController->getJsonData('announcement');
        $this->json = $this->jsonParserController;
    }

    public function index() {
        return view('announcement_list', [
            'announcements' => UserAnnouncement::with([
                'parcelAnnouncement',
                'humanAnnouncement',
                'palletAnnouncement',
                'animalAnnouncement',
                'otherAnnouncement',
            ] )->paginate($this->announcement_json['number_of_search_announcement_in_one_page']),
        ]);
    }

    public function indexForSingleUser() {
        $authorId = auth()->user()->id;
        // dd($authorId  );
        $announcement = view('announcement_list', [
            'announcements' => UserAnnouncement::where('author', $authorId)
                ->with('parcelAnnouncement',
                       'humanAnnouncement',
                       'palletAnnouncement',
                       'animalAnnouncement',
                       'otherAnnouncement')
                ->paginate( $this->json->searchAnnouncementAction()[ 'number_of_search_announcement_in_one_page' ] ),
            ] );
        // dd( $announcement );
        return view('announcement_list', [
            'announcements' => UserAnnouncement::where('author', $authorId)
                ->with('parcelAnnouncement',
                       'humanAnnouncement',
                       'palletAnnouncement',
                       'animalAnnouncement',
                       'otherAnnouncement')

                ->paginate( $this->json->searchAnnouncementAction()[ 'number_of_search_announcement_in_one_page' ] ),
        ]);
    }

    public function create() {
        $directionsData = $this->generateDirectionData();
        $cargoData = $this->json->cargoAction();
        $userData = auth()->user();
        return view( 'announcement_create_form' )
            ->with( "isEditMode", false )
            ->with( 'directionsData', $directionsData )
            ->with( 'userData', $userData )
            ->with( 'cargoData', $cargoData );

    }

    public function store(Request $request) {
        //dodac validator do danych #sema_update
        $announcementData = json_decode($request->announcement_data, true);
        $announcement = $this->getUserAnnouncementObject( $request->title, $announcementData );
        if ( !$request[ 'is_edit_mode' ] ) {
            $userId = auth()->id();
            $announcement->authorUser()->associate( $userId );
            $announcement->save();
            $this->storeCargoTypes( $announcementData, $announcement->id );
            return view( 'announcement_confirmation_info' );
        } else {
            $this->updateAnnouncement( $announcementData );
            return view( 'announcement_confirmation_info_edit' );
        }
    }

    private function getUserAnnouncementObject( $title, $announcementData ) {
        $announcement = new UserAnnouncement ( $this->getAnnouncementObjectArray( $title, $announcementData ) );
        return $announcement;
    }

    private function getAnnouncementObjectArray( $title, $announcementData ) {
        return [
            'direction_sending' =>               $announcementData[ 'country_select_post_code_sending' ],
            'post_code_prefix_sending' =>        $announcementData[ 'prefix_select_' . $announcementData[ 'country_select_post_code_sending' ] . '_post_code_sending' ],
            'post_code_postfix_sending' =>       $announcementData[ 'postfix_select_post_code_sending' ],
            'city_sending' =>                    $announcementData[ 'direction_city_post_code_sending' ],
            'direction_receiving' =>             $announcementData[ 'country_select_post_code_receiving' ],
            'post_code_prefix_receiving' =>      $announcementData[ 'prefix_select_' . $announcementData[ 'country_select_post_code_receiving' ] . '_post_code_receiving' ],
            'post_code_postfix_receiving' =>     $announcementData[ 'postfix_select_post_code_receiving' ],
            'city_receiving' =>                  $announcementData[ 'direction_city_post_code_receiving' ],
            'phone_number' =>                    $announcementData[ 'phone_number' ],
            'email' =>                           $announcementData[ 'email' ],
            'expect_sending_date' =>             $announcementData[ 'expect_sending_date' ],
            'experience_date' =>                 $announcementData[ 'experience_date' ],
            'title' =>                           $title,
            'additional_info' =>                 $announcementData[ 'additional_user_announcement_info' ],
            'parcels_quantity' =>                $announcementData[ 'parcel' ],
            'humans_quantity' =>                 $announcementData[ 'human' ],
            'pallets_quantity' =>                $announcementData[ 'pallet' ],
            'animals_quantity' =>                $announcementData[ 'animal' ],
            'others_quantity' =>                 $announcementData[ 'other' ],
        ];
    }

    private function storeCargoTypes( $request, $announcementId ) {
        $this->storeAnimalData( $request, $announcementId );
        $this->storeHumanData( $request, $announcementId );
        $this->storeOtherData( $request, $announcementId );
        $this->storePalletData( $request, $announcementId );
        $this->storeParcelData( $request, $announcementId );
    }

    private function storeAnimalData ( $request, $announcementId ) {
        //dd( $request);
        for( $i = 0; $i < $request[ 'animal' ]; $i++ ) {
            $animal = new AnimalAnnouncement ( $this->getAnimalDataArray( $request, $announcementId, $i ) );
            $animal->announcementId()->associate( $announcementId  );
            $animal->save();
        }
    }

    private function storeHumanData ( $request, $announcementId ) {
        if ( $request[ 'human' ] > 0 ) {
            $human = new HumanAnnouncement ( $this->getHumanDataArray( $request, $announcementId ) );
            $human->announcementId()->associate( $announcementId  );
            $human->save();
        }
    }

    private function storeOtherData ( $request, $announcementId ) {
        for( $i = 0; $i < $request[ 'other' ]; $i++ ) {
            $other = new OtherAnnouncement ( $this->getOtherDataArray( $request, $announcementId, $i ) );
            $other->announcementId()->associate( $announcementId  );
            $other->save();
        }
    }

    private function storePalletData ( $request, $announcementId ) {
        for( $i = 0; $i < $request[ 'pallet' ]; $i++ ) {
            $pallet = new PalletAnnouncement (  $this->getPalletDataArray( $request, $announcementId, $i ));
            $pallet->announcementId()->associate( $announcementId  );
            $pallet->save();
        }
    }

    private function storeParcelData ( $request, $announcementId ) {
        for( $i = 0; $i < $request[ 'parcel' ]; $i++ ) {
            $parcel = new ParcelAnnouncement ( $this->getParcelDataArray( $request, $announcementId, $i ) );
            $parcel->announcementId()->associate( $announcementId  );
            $parcel->save();
        }
    }

    protected function validator(array $data) {
        $today = new DateTime();
        $experience_max_date = $today->modify('+30 days');
        $experience_max_date_string = $experience_max_date->format('Y-m-d');
        $validator = Validator::make($data, [
            'phone_number' =>                             [ 'required', 'numeric', 'Min Digits:9', 'Max Digits:15' ],
            'email' =>                                    [ 'required', 'email', 'max:255' ],
            'expect_sending_date' =>                      [ 'required', 'max:255', 'after:' . date('Y-m-d', strtotime('-1 day')) ],
            'experience_date' =>                          [ 'required', 'max:255', 'before:' . $experience_max_date_string ],
            'additional_user_announcement_info' =>        [ 'max:10000' ],
        ]);

        return $validator;
    }

    private function validateIsZeroItems( Request $request ) {
        if( $request->parcel == 0 &&
            $request->human == 0 &&
            $request->pallet == 0 &&
            $request->animal == 0 &&
            $request->other == 0 ) {
            return true;
        }
        return false;
    }

    private function validateIsSetAllDirectionData( Request $request ) {
        if( $request->country_select_post_code_sending !== null &&
            $request->country_select_post_code_receiving !== null &&
            $this->checkIsSetPrefix( $request, "_post_code_sending" ) !== false &&
            $this->checkIsSetPrefix( $request, "_post_code_receiving"  ) !== false &&
            $request->postfix_select_post_code_sending !== null &&
            $request->direction_city_post_code_sending !== null &&
            $request->postfix_select_post_code_receiving !== null &&
            $request->direction_city_post_code_receiving !== null ) {
            return true;
        }
        return false;
    }

    private function generateErrorsMessages() {
        return [
            'postfix_select_post_code_sending.max' => __( 'base.postfix_select_post_code_sending_max'),
            'direction_city_post_code_sending.max' => __( 'base.direction_city_post_code_sending_max'),
            'postfix_select_post_code_receiving.max' => __( 'base.postfix_select_post_code_receiving_max'),
            'direction_city_post_code_receiving.max' => __( 'base.direction_city_post_code_receiving_max'),
        ];
    }

    private function validateDirectionsField( $request ) {
        $validator = Validator::make($request->all(), [
            'postfix_select_post_code_sending' =>                        [ 'required', 'max:6' ],
            'direction_city_post_code_sending' =>                        [ 'required', 'max:80' ],
            'postfix_select_post_code_receiving' =>                      [ 'required', 'max:6' ],
            'direction_city_post_code_receiving' =>                      [ 'required', 'max:80' ],
        ], $this->generateErrorsMessages() );
        return $validator;
    }

    private function checkIsSetPrefix( $request, $fieldName ) {
        $directionsData = $this->generateDirectionData();

        foreach( $directionsData as $direction ) {
            $variable_name = 'prefix_select_' . $direction['name'] . $fieldName;
            if( isset( $request->$variable_name ) ) {
                return true;
            }
        }
        return false;
    }

    private function generateEditDataForCargo( $id ) {
        $announcement = $this->getAnnouncementWithRelation( $id );
        $parcelArray = $this->generateParcelRequestSummaryData( $announcement->parcelAnnouncement );
        $palletArray = $this->generatePalletRequestSummaryData( $announcement->palletAnnouncement );
        $animalArray = $this->generateAnimalRequestSummaryData( $announcement->animalAnnouncement );
        $humanArray = $this->generateHumanRequestSummaryData( $announcement->humanAnnouncement );
        $otherArray = $this->generateOtherRequestSummaryData( $announcement->otherAnnouncement );
        return array_merge( $parcelArray, $palletArray, $animalArray, $humanArray, $otherArray );
    }

    public function cargoDataGenerator( Request $request ) {
        $allDirectionData = $this->validateIsSetAllDirectionData( $request );
        $isZeroItems = $this->validateIsZeroItems( $request );
        $validator = $this->validator( $request->all() );
        $directionValidator = $this->validateDirectionsField( $request );


        if ($validator->fails() || $directionValidator->fails() || $isZeroItems || $allDirectionData === false ) {
            return redirect()
                ->back()
                ->withErrors( $validator )
                ->withErrors( $directionValidator )
                ->withInput()
                ->with( 'allDirectionData', $allDirectionData )
                ->with( 'isZeroItems', $isZeroItems );
        } else {
            $editData = [];
            if( $request->is_edit_mode == true ) {
                $editData = $this->generateEditDataForCargo( $request->announcement_id );
                session()->put('_old_input', $editData );
            }
            $cargoData = $this->generateCargoDetailsFormData( $request );
            return view('cargo_details_create_form')
                ->with( 'cargoData', $cargoData );
        }
    }

    public function summary(Request $request) {
        // dd( $request );
        $announcementData = json_decode($request->announcement_data, true);
        $request->merge( $announcementData );
        $request->offsetUnset( 'announcement_data' );
        $title = $this->generateTitleAnnouncement( $announcementData );

        return view('announcement_summary')
            ->with( 'title', $title )
            ->with( 'isSummary', true );
    }

    private function generateTitleAnnouncement( $announcementData ) {
        $title = __( 'base.announcement_title_begin' );
        $iterator = 0;
        foreach( $this->json->cargoAction()[ 'cargo_types' ] as $cargoType ) {
            if( $announcementData[ $cargoType[ 'id' ] ] != 0 ) {
                if( $iterator > 0 ) {
                    $title .= ', ';
                }
                $title .= $announcementData[ $cargoType[ 'id' ] ] . ' ';
                $title .= __FF( 'base', $cargoType[ 'id' ], $announcementData[ $cargoType[ 'id' ] ], 'genitive' );
                $iterator++;
            }
        }
        $title .= '.';

        return $title;
    }

    private function generateDirectionData( ) {
        $directionsData = [];

        foreach( $this->json->directionsAction() as $dir ) {
            $oneDirection = [];
            foreach( $dir as $key => $properties ) {
                $oneDirection[ $key ] = $properties;
            }
            $oneDirection[ 'post_codes' ] = $this->json->getPostCodes( $dir[ 'name' ] );
            $directionsData[ $dir[ 'name' ] ] = $oneDirection;
        }
        return $directionsData;
    }

    private function getAnnouncementWithRelation( $id ) {
        return UserAnnouncement::with(
                'parcelAnnouncement',
                'humanAnnouncement',
                'palletAnnouncement',
                'animalAnnouncement',
                'otherAnnouncement')
            ->findOrFail($id);
    }

    public function show( Request $request, string $id ) {
        $announcement = $this->getAnnouncementWithRelation( $id );
        $cargoData = $this->generateRequestSummaryData( $announcement );
        $request->merge( $cargoData );
        return view('announcement_summary')
            ->with( 'title', $announcement->title )
            ->with( 'isSummary', false );
    }

    public function edit(string $id) {
        $directionsData = $this->generateDirectionData();
        $cargoData = $this->json->cargoAction();
        $userData = auth()->user();
        $announcement = $this->getAnnouncementWithRelation( $id );
        $defaultValues = $this->generateAnnouncementSummaryData( $announcement );
        session()->put('_old_input', $defaultValues );
        return view('announcement_create_form')
            ->with( "isEditMode", true )
            ->with( "announcementId", $id )
            ->with( 'directionsData', $directionsData )
            ->with( 'userData', $userData )
            ->with( 'cargoData', $cargoData );
    }

    private function generateAnnouncementSummaryData( $announcement ) {
        $arrayData = [];
        $arrayData[ 'country_select_post_code_sending'] = strval( $announcement[ 'direction_sending' ] );
        $arrayData[ 'country_select_post_code_receiving'] = strval( $announcement[ 'direction_receiving' ] );
        $arrayData[ 'prefix_select_' . $announcement[ 'direction_sending' ] . '_post_code_sending'] = strval( $announcement[ 'post_code_prefix_sending' ] );
        $arrayData[ 'prefix_select_' . $announcement[ 'direction_receiving' ] . '_post_code_receiving'] = strval( $announcement[ 'post_code_prefix_receiving' ] );
        $arrayData[ 'postfix_select_post_code_sending'] = strval( $announcement[ 'post_code_postfix_sending' ] );
        $arrayData[ 'direction_city_post_code_sending'] = strval( $announcement[ 'city_sending' ] );
        $arrayData[ 'postfix_select_post_code_receiving'] = strval( $announcement[ 'post_code_postfix_receiving' ] );
        $arrayData[ 'direction_city_post_code_receiving'] = strval( $announcement[ 'city_receiving' ] );
        $arrayData[ 'phone_number'] = strval( $announcement[ 'phone_number' ] );
        $arrayData[ 'email'] = strval( $announcement[ 'email' ] );
        $arrayData[ 'expect_sending_date'] = strval( $announcement[ 'expect_sending_date' ] );
        $arrayData[ 'experience_date'] = strval( $announcement[ 'experience_date' ] );
        $arrayData[ 'parcel'] = strval( $announcement[ 'parcels_quantity' ] );
        $arrayData[ 'human'] = strval( $announcement[ 'humans_quantity' ] );
        $arrayData[ 'pallet'] = strval( $announcement[ 'pallets_quantity' ] );
        $arrayData[ 'animal'] = strval( $announcement[ 'animals_quantity' ] );
        $arrayData[ 'other'] = strval( $announcement[ 'others_quantity' ] );
        $arrayData[ 'additional_user_announcement_info'] = strval( $announcement[ 'additional_info' ] );

        return $arrayData;
    }

    private function generateRequestSummaryData( $announcement ) {
        $announcementArray = $this->generateAnnouncementSummaryData( $announcement );
        $parcelArray = $this->generateParcelRequestSummaryData( $announcement->parcelAnnouncement );
        $palletArray = $this->generatePalletRequestSummaryData( $announcement->palletAnnouncement );
        $animalArray = $this->generateAnimalRequestSummaryData( $announcement->animalAnnouncement );
        $humanArray = $this->generateHumanRequestSummaryData( $announcement->humanAnnouncement );
        $otherArray = $this->generateOtherRequestSummaryData( $announcement->otherAnnouncement );

        return array_merge( $announcementArray, $parcelArray, $palletArray, $animalArray, $humanArray, $otherArray );
    }

    private function generateParcelRequestSummaryData ( $array ) {
        $dataArray = [];
        $iterator = 0;
        foreach( $array as $parcel ) {
            $dataArray[ 'parcel_weight_' . $iterator ] = strval( $parcel[ 'height' ] );
            $dataArray[ 'parcel_length_' . $iterator ] = strval( $parcel[ 'length' ] );
            $dataArray[ 'parcel_width_' . $iterator ] = strval( $parcel[ 'width' ] );
            $dataArray[ 'parcel_height_' . $iterator ] = strval( $parcel[ 'height' ] );
            $iterator++;
        }

        return $dataArray;
    }

    private function generatePalletRequestSummaryData ( $array ) {
        $dataArray = [];
        $iterator = 0;
        foreach( $array as $pallet ) {
            $dataArray[ 'pallet_weight_' . $iterator ] = strval( $pallet[ 'height' ] );
            $dataArray[ 'pallet_length_' . $iterator ] = strval( $pallet[ 'length' ] );
            $dataArray[ 'pallet_width_' . $iterator ] = strval( $pallet[ 'width' ] );
            $dataArray[ 'pallet_height_' . $iterator ] = strval( $pallet[ 'height' ] );
            $iterator++;
        }

        return $dataArray;
    }

    private function generateAnimalRequestSummaryData ( $array ) {
        $dataArray = [];
        $iterator = 0;
        foreach( $array as $animal ) {
            $dataArray[ 'animal_type_' . $iterator ] = strval( $animal[ 'animal_type' ] );
            $dataArray[ 'animal_weight_' . $iterator ] = strval( $animal[ 'weight' ] );
            $dataArray[ 'animal_short_description_' . $iterator ] = strval( $animal[ 'animal_description' ] );
            $iterator++;
        }

        return $dataArray;
    }

    private function generateHumanRequestSummaryData ( $array ) {
        $dataArray = [];
        $iterator = 0;
        foreach( $array as $human ) {
            $dataArray[ 'human_adult_' . $iterator ] = strval( $human[ 'adult' ] );
            $dataArray[ 'human_kids_' . $iterator ] = strval( $human[ 'kids' ] );
            $iterator++;
        }

        return $dataArray;
    }

    private function generateOtherRequestSummaryData ( $array ) {
        $dataArray = [];
        $iterator = 0;
        foreach( $array as $others ) {
            $dataArray[ 'other_description_' . $iterator ] = strval( $others[ 'description' ] );
            $iterator++;
        }

        return $dataArray;
    }

    public function update(Request $request, string $id) {
    }

    private function updateAnnouncement( $announcementData ) {
        $announcementId = $announcementData[ 'announcement_id' ];
        $title = $this->generateTitleAnnouncement( $announcementData );
        $announcementArray = $this->getAnnouncementObjectArray( $title, $announcementData );
        $userAnnouncement = $this->getAnnouncementWithRelation( $announcementData[ 'announcement_id' ] );
        $this->deleteRelations( $userAnnouncement );
        UserAnnouncement::where('id', $announcementId)->update( $announcementArray );
        $this->storeCargoTypes( $announcementData, $announcementId );

    }

    public function destroy( string $id ) {
        return view( 'announcement_delete_asking' )
            ->with( 'announcementId', $id);
    }

    public function destroyConfirm( string $id ) {
        $announcement = $this->getAnnouncementWithRelation( $id );
        $this->storeAnnouncementArchive( $announcement );
        $this->deleteAnnouncement( $announcement );
        return view( 'announcement_delete_confirm' )
            ->with( 'announcementId', $id);
    }

    private function storeAnnouncementArchive( $request ) {
        $announcementArchive = new UserAnnouncementArchive ( $request->getAttributes() );
        $userId = auth()->id();
        $announcementArchive->authorUser()->associate( $userId );
        $announcementArchive->save();
        $announcementId = $request->id;
        $this->storeAnimalDataArchive( $request->animalAnnouncement, $announcementId );
        $this->storeHumanDataArchive( $request->humanAnnouncement, $announcementId );
        $this->storeOtherDataArchive( $request->otherAnnouncement, $announcementId );
        $this->storePalletDataArchive( $request->palletAnnouncement, $announcementId );
        $this->storeParcelDataArchive( $request->parcelAnnouncement, $announcementId );
    }

    private function deleteAnnouncement( $announcement ) {
        $this->deleteRelations( $announcement );
        $announcement->delete();
    }

    private function deleteRelations( $announcement ) {
        $announcement->parcelAnnouncement()->delete();
        $announcement->humanAnnouncement()->delete();
        $announcement->palletAnnouncement()->delete();
        $announcement->animalAnnouncement()->delete();
        $announcement->otherAnnouncement()->delete();
    }

    private function storeAnimalDataArchive( $request, $announcementId ) {
        foreach( $request as $animal ) {
            $animal = new AnimalAnnouncementArchive ( $animal->getAttributes()  );
            $animal->announcementId()->associate( $announcementId  );
            $animal->save();
        }
    }

    private function storeHumanDataArchive( $request, $announcementId ) {
        foreach( $request as $human ) {
            $human = new HumanAnnouncementArchive ( $human->getAttributes()  );
            $human->announcementId()->associate( $announcementId  );
            $human->save();
        }
    }

    private function storeOtherDataArchive( $request, $announcementId ) {
        foreach( $request as $other ) {
            $other = new OtherAnnouncementArchive ( $other->getAttributes()  );
            $other->announcementId()->associate( $announcementId  );
            $other->save();
        }
    }

    private function storePalletDataArchive( $request, $announcementId ) {
        foreach( $request as $pallet ) {
            $pallet = new PalletAnnouncementArchive ( $pallet->getAttributes()  );
            $pallet->announcementId()->associate( $announcementId  );
            $pallet->save();
        }
    }

    private function storeParcelDataArchive( $request, $announcementId ) {

        foreach( $request as $parcel ) {
            $parcel = new ParcelAnnouncementArchive ( $parcel->getAttributes() );
            $parcel->announcementId()->associate( $announcementId  );
            $parcel->save();
        }
    }

    private function generateCargoDetailsFormData( $request ) {
        $allCargoDetails = [];
        $formData = $request->all();
        $cargoData = $this->json->cargoAction();
        foreach( $cargoData[ 'cargo_types' ] as $cargo ) {
            if( $formData[ $cargo[ 'id' ] ] > 0 ) {
                $cargoId = $cargo[ 'id' ];
                $cargoQuantity = $formData[ $cargo[ 'id' ] ];
                $cargoParams = [];
                foreach( $cargo[ 'params' ] as $key => $param ) {
                    $cargoParams[ $param[ 'id' ] ] = $param[ 'type' ];
                    if ( $param[ 'type' ] == "textarea" ) {
                        $cargoParams[ $param[ 'id' ] . '_size' ] = $param[ 'textarea_size' ];
                    }
                }
                $oneParamDetail = [];
            $oneParamDetail[ 'cargoId' ] = $cargoId;
            $oneParamDetail[ 'cargoQuantity' ] = $cargoQuantity;
            $oneParamDetail[ 'cargoParams' ] = $cargoParams;
            $allCargoDetails[] = $oneParamDetail;
            }
        }

        return $allCargoDetails;
    }

    private function getAnimalDataArray( $request, $announcementId, $index ) {
        return [
            'announcement_id' =>                  $announcementId,
            'animal_type' =>                      $request[ 'animal_type_' . $index ],
            'weight' =>                           $request[ 'animal_weight_' . $index ],
            'animal_description' =>               $request[ 'animal_short_description_' . $index ],
        ];
    }

    private function getHumanDataArray( $request, $announcementId ) {
        return [
            'announcement_id' =>           $announcementId,
            'adult' =>                     $request[ 'human_adult_0' ],
            'kids' =>                      $request[ 'human_kids_0' ],
        ];
    }

    private function getOtherDataArray( $request, $announcementId, $index ) {
        return [
            'announcement_id' =>                  $announcementId,
            'description' =>                      $request[ 'other_description_' . $index ],
        ];
    }

    private function getPalletDataArray( $request, $announcementId, $index ) {
        return [
            'announcement_id' =>             $announcementId,
            'weight' =>                      $request[ 'pallet_weight_' . $index ],
            'length' =>                      $request[ 'pallet_length_' . $index ],
            'width' =>                       $request[ 'pallet_width_' . $index],
            'height' =>                      $request[ 'pallet_height_' . $index ],
        ];
    }

    private function getParcelDataArray( $request, $announcementId, $index ) {
        return [
            'announcement_id' =>             $announcementId,
            'weight' =>                      $request[ 'parcel_weight_' . $index ],
            'length' =>                      $request[ 'parcel_length_' . $index ],
            'width' =>                       $request[ 'parcel_width_' . $index ],
            'height' =>                      $request[ 'parcel_height_' . $index ],
        ];
    }

    private $json;
}