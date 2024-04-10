<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\JsonParserController;
use App\Http\Controllers\CustomUserController;

class AccountController extends Controller
{
    private $json;

    function __construct(JsonParserController $jsonParserController) {
        $this->json = $jsonParserController;
    }

    public function registerAccount() {
        return $this->create();
    }

    public function confirmAccountType( Request $request ){
        $accountType = $request->account_type_input_id;

        return view('accounts.confirmed_account')
                ->with( 'accountType', $accountType )
                ->with( 'isEdit', false );
    }

    public function index() {}

    public function create() {
        $accountData = $this->json->getJsonData('accounts');
        return view('accounts.account_register')
            ->with('accountData', $accountData);
    }

    public function store( Request $request ) {
        $user = new CustomUserController;
        $valid = $user->create( $request );

        if ($valid->fails()) {
            $accountType = $request->account_type;
            return redirect()
                ->route('confirmed_account', $request->all() )
                ->withErrors( $valid )
                ->withInput()
                ->with( 'accountType', $accountType )
                ->with( 'isEdit', false );
        } else {
            $user->store( $request );
            return redirect()->route('account_last_confirmed' );
        }
    }

    public function show(string $id) {}

    public function edit( ) {
        //dd( "edit" );
        $accountData = $this->json->getJsonData('accounts');
        return view('accounts.edit_account')
                    ->with( 'accountData', $accountData )
                    ->with( 'accountType', auth()->user()->account_type );
    }

    public function update( Request $request ) {
        $user = auth()->user();
        $user->account_type = $request->input('account_type_input_id');
        $user->update();

        return view('accounts.edit_account_confirm_last');
    }

    public function destroy(string $id) {}

}
