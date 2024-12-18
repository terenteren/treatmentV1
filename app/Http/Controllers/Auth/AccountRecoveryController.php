<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AccountRecoveryService;
use Exception;
use Illuminate\Http\Request;

class AccountRecoveryController extends Controller
{
    protected $accountRecoveryService;

    public function __construct(AccountRecoveryService $service)
    {
        $this->accountRecoveryService = $service;
    }

    public function findUser()
    {
        return view('account.findUser');
    }

    public function findPassword()
    {
        return view('account.findPassword');
    }

    public function resetPassword()
    {
        return view('account.resetPassword');
    }

    public function handleResetPassword(Request $request)
    {
        // 비밀번호 유효성 검사
        $request->validate([
            'password' => 'required|min:6|confirmed', // `confirmed`는 password_confirm 필드와 값 일치를 확인합니다.
        ]);

        try {
            // 비즈니스 로직 실행
            $this->accountRecoveryService->resetPassword($request->password);

            // 성공 여부를 뷰로 전달
            return view('account.resetPassword', ['success' => true]);
        } catch (Exception $e) {
            // 예외 발생 시 오류 메시지와 함께 이전 페이지로 리다이렉트
            return redirect()->route('account.findPassword')->withErrors($e->getMessage());
        }
    }


}
