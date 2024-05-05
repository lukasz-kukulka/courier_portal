<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserCompany;
use App\Models\UserModel;
class CustomUserController extends Controller
{
    public function create( Request $request ){
        return $this->validator( $request->all() );
    }

    private function validator( $data) {
        $userRules = [
            'name' => ['required', 'string', 'max:55'],
            'surname' => ['required', 'string', 'max:55'],
            'phone_number' => ['required', 'numeric', 'digits_between:9,14'],
            'email' => ['required', 'email'],
            'd_o_b' => ['required', 'date', 'before:2010-01-01'],
        ];
        $companyRules = [];

        if ( array_key_exists( 'company_fields_checkbox', $data ) ) {
            $companyRules = [
                'company_name' => ['nullable', 'string', 'max:99'],
                'company_address' => ['nullable', 'string', 'max:55'],
                'company_phone_number' => ['nullable', 'string', 'min:9', 'max:14'],
                'company_post_code' => ['nullable', 'string', 'max:9'],
                'company_city' => ['nullable', 'string', 'max:44'],
                'company_country' => ['nullable', 'string', 'max:44'],
            ];
        }

        $validateResult = Validator::make($data, array_merge( $userRules, $companyRules ) );
        return $this->setAttributesFormNames( $validateResult );
    }

    private function setAttributesFormNames( $data ) {
        $data->setAttributeNames([
            'name' => __('base.name'),
            'surname' => __('base.surname'),
            'phone_number' => __('base.phone_number'),
            'email' =>  __('base.email'),
            'company_name' => __('base.company_name'),
            'company_address' => __('base.company_address'),
            'company_phone_number' => __('base.company_phone_number'),
            'company_post_code' => __('base.company_post_code'),
            'company_city' => __('base.company_city'),
            'company_country' => __('base.company_country'),
            'd_o_b' => __('base.d_o_b'),
        ]);

        return $data;
    }

    public function store( Request $request ) {
        $user = auth()->user();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->phone_number = $request->input('phone_number');
        $user->email = $request->input('email');
        $user->d_o_b = $request->input('d_o_b');
        $user->group = $request->input('account_type');
        $user->account_type = $request->input('account_type');
        $user->is_company = $request->input('company_fields_checkbox') != null ? true : false;

        $user->save();

        if( $user->is_company ) {
            $this->storeCompany( $request );
        }

    }

    public function show(string $id) {

    }

    public function edit() {
        $accountType = auth()->user()->account_type;
        $company = UserModel::with('company')->find( auth()->user()->id )->company;
        return view('accounts.confirmed_account')
                ->with( 'accountType', $accountType )
                ->with( 'isEdit', true )
                ->with( 'company', $company );
    }

    public function editUserSummary() {
        return view( 'accounts.edit_account_confirm_last' );
    }

    public function update( Request $request ) {
        $valid = $this->create( $request );

        if ($valid->fails()) {
            $accountType = $request->account_type;
            return redirect()
                ->route('user_edit_profile', $request->all() )
                ->withErrors( $valid )
                ->withInput()
                ->with( 'accountType', $accountType );
        } else {
            $this->updateUser( $request );
            $this->updateCompany( $request );
            return redirect()->route('user_edit_summary' );
        }
    }

    private function updateUser( $request ) {
        $user = UserModel::find( auth()->user()->id );
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->d_o_b = $request->d_o_b;
        $user->save();
    }

    private function updateCompany( $request ) {
        $company = UserModel::find( auth()->id() )->company;

        if ( $request->input('company_fields_checkbox') == null ) {
            if ( $company !== null ) {
                $company->delete();
            }
        } else {
            if ( $company !== null ) {
                $company->company_name = $request->input('company_name');
                $company->company_address = $request->input('company_address');
                $company->company_post_code = $request->input('company_post_code');
                $company->company_city = $request->input('company_city');
                $company->company_country = $request->input('company_country');
                $company->company_phone_number = $request->input('company_phone_number');
                $company->company_register_link = $request->input('company_register_link');
                $company->save();
            } else {
                $this->storeCompany( $request );
            }
        }
    }

    private function storeCompany( $request ) {
        $company = new UserCompany ( [
            'company_name' => $request->input('company_name'),
            'company_address' => $request->input('company_address'),
            'company_post_code' => $request->input('company_post_code'),
            'company_city' => $request->input('company_city'),
            'company_country' => $request->input('company_country'),
            'company_phone_number' => $request->input('company_phone_number'),
            'company_register_link' => $request->input('company_register_link'),
        ] );
        $userId = auth()->id();
        $company->authorUser()->associate( $userId );
        $company->save();

    }

    public function destroy( $id = 0 ){
        return view('confirm_access_question')
            ->with('id', 'delete_user_account')
            ->with('question', __('base.user_account_delete_question'))
            ->with('yesRoute', 'confirmDestroy')
            ->with('noRoute', 'profile');
    }

    public function confirmedDestroy() {
        $userId = auth()->user()->id;
        $user = User::with( 'userAnnouncement', 'courierAnnouncement', 'company' )->find( $userId );
        dd( $user );
        Auth::logout();


    }
}