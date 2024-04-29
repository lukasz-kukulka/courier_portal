<?php

namespace App\Http\Controllers;

use App\Models\GoogleLoginData;
use App\Models\UserAnnouncement;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class SocialMediaLoginController extends Controller {

    public function googleRedirect() {
        $google = Socialite::driver('google')->redirect();
        return $google;
    }

    public function googleCallback() {
        $user = Socialite::driver('google')->user();
        $this->checkEmailIfExist( $user, 'google' );
        dd( $user );
        // "sub" => "102813310526218291498"
        // "name" => "Lukasz K/Nanautzin"
        // "given_name" => "Lukasz"
        // "family_name" => "K/Nanautzin"
        // "picture" => "https://lh3.googleusercontent.com/a/ACg8ocKHRna4oSAm8Z25sFUCKhFo1A59ZjA6O2eQl7ILZG2r1ztBb2EQZQ=s96-c"
        // "email" => "qqla83@gmail.com"
        // "email_verified" => true
        // "locale" => "en-GB"
    }

    public function storeGoogleData( Request $request ) {
        // $animal = new GoogleLoginData ( $this->getAnimalDataArray( $request, $announcementId, $i ) );
        // $animal->announcementId()->associate( $announcementId  );
        // $animal->save();
    }

    public function facebookRedirect() {
        $facebook = Socialite::driver('facebook')->redirect();
        return $facebook;
    }

    public function facebookCallback() {
        $user = Socialite::driver('facebook')->user();
        dd( $user );
    }

    private function checkEmailIfExist( $socialUser, $media ) {
        if( $socialUser[ 'email_verified' ] == true ) {
            $user = auth()->user();
            if( $user->email == $socialUser[ 'email' ] ) {
                if( $this->checkIfEmailIsConnected( $user->id, $media ) == false ) {
                    return view('confirm_page')
                        ->with( "id", 'social_account_connected' )
                        ->with( "question", __( 'base.social_account_connected_question' ) )
                        ->with( "data", $socialUser )
                        ->with( "route_name", $media . 'store' );
                }
            }
        }
        dd( 'error email niezweryfikowany przez social media');
        return false;
    }

    private function checkIfEmailIsConnected( $id, $media ) {
        $user = UserAnnouncement::with(
            $media )
        ->findOrFail( $id );
        return $user->$media[ 'account_merged' ];

    }


}