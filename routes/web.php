<?php

use App\Http\Controllers\Auth\AccountRecoveryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\Drug\DrugController;
use App\Http\Controllers\Drug\DrugRecordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// 핸드폰번호 문자인증 관련 (아이코드icode)
Route::post('send-verification-code', [VerificationController::class, 'requestVerificationCode']);
Route::post('verify-code', [VerificationController::class, 'verifyCode']);
Route::post('verifyCodeAndReturnEmail', [VerificationController::class, 'verifyCodeAndReturnEmail'])->name('verifyCodeAndReturnEmail');
Route::post('verifyCodeAndReturnPassReset', [VerificationController::class, 'verifyCodeAndReturnPassReset'])->name('verifyCodeAndReturnPassReset');
Route::post('delete-verification-code', [VerificationController::class, 'deleteVerificationCode']);
Route::post('check-phone-number', [VerificationController::class, 'checkPhoneNumber']);

// 이메일 비밀번호 찾기
Route::get('findUser', [AccountRecoveryController::class, 'findUser'])->name('findUser');
Route::get('findPassword', [AccountRecoveryController::class, 'findPassword'])->name('findPassword');
Route::get('resetPassword', [AccountRecoveryController::class, 'resetPassword'])->name('resetPassword');
Route::get('reset-password', [AccountRecoveryController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('reset-password', [AccountRecoveryController::class, 'handleResetPassword'])->name('account.handleResetPassword');

Route::post('check-email', [UserController::class, 'checkEmail']);

// 로그인 전
Route::middleware(['guest'])->group(function () {
    // login
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginStore'])->name('loginStore');

    // signup1
    Route::get('signup1', [SignupController::class, 'showSignup1'])->name('signup1');
    Route::post('processSignup1', [SignupController::class, 'processSignup1'])->name('processSignup1');
    // signup2
    Route::get('signup2', [SignupController::class, 'showSignup2'])->name('signup2');
    Route::post('processSignup2', [SignupController::class, 'processSignup2'])->name('processSignup2');
});

// 로그인 후
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/drug-record/{id}', [HomeController::class, 'showDrugRecord'])->name('showDrugRecord');


    // signup3
    Route::get('signup3', [SignupController::class, 'showSignup3'])->name('signup3');
    Route::post('processSignup3', [SignupController::class, 'processSignup3'])->name('processSignup3');
    Route::get('signup4', [SignupController::class, 'showSignup4'])->name('signup4');
    Route::post('processSignup4', [SignupController::class, 'processSignup4'])->name('processSignup4');
    Route::get('signup5', [SignupController::class, 'showSignup5'])->name('signup5');
    Route::post('processSignup5', [DrugRecordController::class, 'processSignup5'])->name('processSignup5');

    // drug
    Route::get('drugSearch', [DrugController::class, 'drugSearch'])->name('drugSearch');
    Route::get('drugSearchProc', [DrugController::class, 'drugSearchProc'])->name('drugSearchProc');

    // mypage
    Route::get('mypage', [UserController::class, 'mypage'])->name('mypage');
    // editMyPage
    Route::get('editMyPage', [UserController::class, 'editMyPage'])->name('editMyPage');
    Route::post('editMyPageStore', [UserController::class, 'editMyPageStore'])->name('editMyPageStore');


    // 자녀
    Route::get('addChild', [ChildController::class, 'addChild'])->name('addChild');
    Route::post('addChildStore', [ChildController::class, 'addChildStore'])->name('addChildStore');
    Route::get('updateChild/{id}', [ChildController::class, 'updateChild'])->name('updateChild');
    Route::post('updateChildStore/{id}', [ChildController::class, 'updateChildStore'])->name('updateChildStore');

    Route::get('showSelectedChild', [ChildController::class, 'showSelectedChild'])->name('showSelectedChild');
    Route::post('changeSelectedChildStore', [ChildController::class, 'changeSelectedChildStore'])->name('changeSelectedChildStore');

//    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});


Route::middleware(['auth:api'])->group(function () {

});

//Route::get('/store-drugs', [DrugController::class, 'storeDrugData']); // 약물 파일 데이터 불러와 저장
