<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeclarationPrintController;
use App\Http\Controllers\UserAnnouncementController;
use App\Http\Controllers\CourierAnnouncementController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomUserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
{ //############################### BASIC ##############################
    Auth::routes( ['verify' => true] );

    Route::get('/', function () { return view('welcome'); })->name('main');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/no_access', function () { return view('no_access'); })->name('no_access')->middleware( ['auth', 'verified'] );
} //####################################################################


{ //############################### USER ACCOUNT #######################
    Route::get('user_edit_profile', [ CustomUserController::class, 'edit'] )
        ->name('user_edit_profile')
        ->middleware( ['auth', 'verified'] );

    Route::post('user_update_profile', [ CustomUserController::class, 'update'] )
        ->name('user_update_profile')
        ->middleware( ['auth', 'verified'] );

    Route::get('user_edit_summary', [ CustomUserController::class, 'editUserSummary'] )
        ->name('user_edit_summary')
        ->middleware( ['auth', 'verified'] );
} //####################################################################


{ //############################### DECLARATION ########################
    Route::get('/cn22', function () { return view('cn22'); })
        ->name('cn22')
        ->middleware( ['auth', 'verified'] );

    Route::get('/cn23', function () { return view('cn23'); })
        ->name('cn23')
        ->middleware( ['auth', 'verified'] );

    Route::post('/pdf_gen', function (Request $request) {
        $postData = $request->all();
        $pdf = app()->makeWith(DeclarationPrintController::class, ['post' => $postData]);
        return $pdf->generatePDFDocument(); })
            ->name('pdf_gen')
            ->middleware( ['auth', 'verified'] ); // nie wiem czy bedzie działać trzeba przetestować
} //####################################################################


{ //############################### ACCOUNT TYPES ######################
    Route::get('register_account', [ AccountController::class, 'registerAccount'] )
        ->middleware(['auth', 'verified'])
        ->name('register_account');

    Route::post('accounts/confirmed_account', [ AccountController::class, 'confirmAccountType'] )
        ->name('confirmed_account')
        ->middleware( ['auth', 'verified'] );

    Route::post('accounts/confirmed_account_last', [ AccountController::class, 'store'] )
        ->name('create_person_data')
        ->middleware( ['auth', 'verified'] );

    Route::get('/accounts/confirmed_account_last', function () { return view('accounts.confirmed_account_last'); })
        ->name('account_last_confirmed')
        ->middleware( ['auth', 'verified'] );

    Route::get('edit_type_account', [ AccountController::class, 'edit'] )
        ->middleware(['auth', 'verified'])
        ->name('edit_type_account');

    Route::post('accounts/confirm_edit_account', [ AccountController::class, 'update'] )
        ->name('confirm_edit_account')
        ->middleware( ['auth', 'verified'] );

    Route::get('/accounts/edit_account_confirm_last', function () { return view('accounts.edit_account_confirm_last'); })
        ->name('edit_account_confirm_last')
        ->middleware( ['auth', 'verified'] );
} //####################################################################


{ //############################### USER ANNOUNCEMENT ##################
    Route::resource('user_announcement', UserAnnouncementController::class)
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro']);
    Route::get('generate_user_announcement', [ UserAnnouncementController::class, 'create'] )
        ->middleware( ['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'] )
        ->name('generate_user_announcement');
    Route::post('cargo_generator', [UserAnnouncementController::class, 'cargoDataGenerator'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('cargo_generator');
    Route::get('user_announcements_list', [UserAnnouncementController::class, 'indexForSingleUser'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('user_announcements_list');
    Route::post('announcement_confirm_destroy/{id}', [UserAnnouncementController::class, 'destroyConfirm'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('announcement_confirm_destroy');
    Route::post('user_announcement_summary', [UserAnnouncementController::class, 'summary'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('user_announcement_summary');
} //####################################################################


{ //############################### COURIER ANNOUNCEMENT ##################
    Route::resource('courier_announcement', CourierAnnouncementController::class)->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro']);
    Route::get('courier_announcement_creator', [CourierAnnouncementController::class, 'create'])
        ->middleware(['auth', 'verified', 'account_check:courier_pro,courier'])
        ->name('courier_announcement_creator');
    Route::post('courier_announcement_generator', [CourierAnnouncementController::class, 'generateCourierAnnouncement'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('courier_announcement_generator');
    Route::post('courier_announcement_summary', [CourierAnnouncementController::class, 'summary'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('courier_announcement.summary');
    Route::get('courier_announcement_index', [CourierAnnouncementController::class, 'index'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('courier_announcement.index');
    Route::post('courier_announcement_summary_edit', [CourierAnnouncementController::class, 'editCreation'])
        ->middleware(['auth', 'verified', 'account_check:courier,courier_pro,standard,standard_pro'])
        ->name('courier_announcement.editCreation');
} //####################################################################




