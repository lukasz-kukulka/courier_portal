<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Helpers\translate_helpers;
use App\Models\UserAnnouncement;
use Illuminate\Support\Facades\Auth;
use DateTime;
class UserAnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    // protected function validator(array $data)
    // {
    //     // $validate_result = Validator::make($data, [
    //     //     'name' => ['required', 'string', 'max:55'],
    //     //     'surname' => ['required', 'string', 'max:55'],
    //     //     'phone_number' => ['required', 'numeric', 'digits_between:9,14'],
    //     //     'd_o_b' => ['required', 'date', 'before:2010-01-01'],
    //     // ]);

    //     // if ( $data['name'] == 'account_type' ) {
    //     //     $company_validate_result = Validator::make($data, [
    //     //         'company_name' => ['required', 'string', 'max:99'],
    //     //         'company_address' => ['required', 'string', 'max:55'],
    //     //         'company_phone_number' => ['required', 'numeric', 'min:9', 'max:14'],
    //     //         'company_post_code' => ['required', 'string', 'max:9'],
    //     //         'company_city' => ['required', 'string', 'max:44'],
    //     //         'company_country' => ['required', 'string', 'max:44'],
    //     //     ]);

    //     //     $validate_result->merge( $company_validate_result );
    //     // }
    // }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view( 'announcement_create_form' );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $data = $request->all();
        $announcement_data = json_decode( $data['announcement_data'], true);
        $announcement = new UserAnnouncement ( [
            'direction' =>                      $announcement_data[ 'direction' ],
            'post_code_sending' =>              $announcement_data[ 'post_code_sending' ],
            'post_code_receiving' =>            $announcement_data[ 'post_code_receiving' ],
            'phone_number' =>                   $announcement_data[ 'phone_number' ],
            'email' =>                          $announcement_data[ 'email' ],
            'expect_sending_date' =>            $announcement_data[ 'expect_sending_date' ],
            'experience_date' =>                $announcement_data[ 'experience_date' ],
            'title' =>                          $data[ 'title' ],
            'order_description_short' =>        $data[ 'short_announcement' ],
            'order_description_long' =>         $data[ 'announcement_details' ],
            'parcels_quantity' =>               $announcement_data[ 'parcel' ],
            'humans_quantity' =>                $announcement_data[ 'human' ],
            'pallets_quantity' =>               $announcement_data[ 'pallet' ],
            'animals_quantity' =>               $announcement_data[ 'animal' ],
            'others_quantity' =>                $announcement_data[ 'other' ],
        ] );
        $userId = auth()->id();
        $announcement->authorUser()->associate( $userId );
        $announcement->save();
        //dd( $request->all() );
    }

    protected function validator(array $data) {
        $today = new DateTime();
        $experience_max_date = $today->modify('+30 days');
        $experience_max_date_string = $experience_max_date->format('Y-m-d');
        $validator = Validator::make($data, [
            'post_code_sending' => [ 'required', 'string', 'max:10' ],
            'post_code_receiving' => [ 'required', 'string', 'max:10' ],
            'phone_number' => [ 'required', 'numeric', 'Min Digits:9', 'Max Digits:15' ],
            'email' => [ 'required', 'email', 'max:255' ],
            'expect_sending_date' => [ 'required', 'max:255', 'after:' . date('Y-m-d') ],
            'experience_date' => [ 'required', 'max:255', 'before:' . $experience_max_date_string ],
        ]);

        return $validator;
    }

    public function show(string $id)
    {
        //
    }

    public function cargoDataGenerator(Request $request)
    {
        //
        $validator = $this->validator( $request->all() );
        if ($validator->fails()) {
            //dd($validator );
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = $request->all();
            array_shift( $_POST );
            return view('cargo_details_create_form', [
                'data' => $data,
                'announcement_data' => $_POST,
            ]);
        }
    }

    public function summary(Request $request) {
        $data = $request->all();
        $announcement = $this->generateAnnouncement( $data );
        return view('announcement_summary', [
            // 'data' => $data,
            'announcement' => $announcement,
        ]);
    }
    private function generateAnnouncement( $data ) {
        $serialized_data[ 'cargo_data' ] = $this->serializeCargoData( $data );
        $serialized_data[ 'title' ] = $this->generateTitleAnnouncement( $data[ 'quantity' ] );
        $serialized_data[ 'short_announcement' ] = $this->generateShortAnnouncement( $data[ 'announcement_data' ] );
        $serialized_data[ 'announcement_details' ] = $this->generateAnnouncementDetails( $serialized_data );
        //dd( $data );
        //dd( $serialized_data );
        return $serialized_data;
    }

    private function generateShortAnnouncement( $data ) {
        $announcement = '';
        $json = json_decode( $data );
        $announcement .= __( 'base.' . $json->direction . '' );
        $announcement .= __( 'base.post_code_send' ) . $json->post_code_sending . "\n";
        $announcement .= __( 'base.post_code_delivery' ) . $json->post_code_receiving . "\n";
        $announcement .= __( 'base.announcement_date' ) . $json->expect_sending_date . ". ";
        $announcement .= __( 'base.elastic_date' );
        return $announcement;
    }

    private function generateAnnouncementDetails( $data ) {
        $announcement = '';
        $iterator = 1;
        $prev_type = '';
        foreach( $data['cargo_data'] as $cargo ) {
            if ( $cargo['id'] == 'human' ) {
                $announcement .= __FF( 'base', $cargo['id'], 2, 'nominative', true ) . ": ";
            } else {
                if ( $prev_type == $cargo['id'] ) {
                    $iterator++;
                } else {
                    $iterator = 1;
                }
                $announcement .= $iterator . '. ' .  __FF( 'base', $cargo['id'], 1, 'nominative', true ) . ": ";
            }

            for( $i = 0; $i < count( $cargo ) - 1; $i++ ) {
                $announcement .= __( 'base.' . $cargo[ $i ][ 'id' ] . '_base' ) . " = " . $cargo[ $i ][ 'value' ] . " " . __( 'base.' . $cargo[ $i ][ 'id' ] ) . ' ';
            }
            $announcement .= "\n";
            $prev_type = $cargo['id'];
        }
        return $announcement;
    }

    private function generateTitleAnnouncement( $data ) {
        $title = '';
        $title .= __( 'base.announcement_title' );
        $json = json_decode( $data );
        foreach( $json as $cargo_key => $cargo_value ) {
            $separator = ', ';
            if ( $cargo_key === count( $json ) - 1 ) {
                $separator = ".\n";
            }
            $title .= $cargo_value->value . " " . __FF( 'base', $cargo_value->id, $cargo_value->value, 'genitive' ) . $separator;
        }

        return $title;
    }
    public function edit(string $id)
    {
        //
    }

    private function serializeCargoData( $data ) {
        $json_data = json_decode( $data[ 'json_data' ], true );
        $serialize_table = [];
        $if_was_human_type = false;
        foreach ( $json_data as $cargo_data) {
            if ( $cargo_data[ 'id' ] !== 'human' || $if_was_human_type === false ) {
                $cargo_param_table = [];
                $cargo_param_table['id'] = $cargo_data[ 'id' ];
                $iterator = 0;
                foreach ( $cargo_data[ 'params' ] as $param ) {
                    $element = [];
                    $element[ 'id' ] = $param[ 'id' ];
                    $element[ 'value' ] = $_POST[ $cargo_data[ 'id' ] . "_" . $param[ 'id' ] . "_" . $iterator ];
                    array_push( $cargo_param_table, $element );
                }
                array_push( $serialize_table, $cargo_param_table );
            }
            if ( $cargo_data[ 'id' ] == 'human' ) {
                $if_was_human_type = true;
            }
        }
        return $serialize_table;
    }
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
}