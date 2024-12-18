<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VerificationController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * 휴대폰번호 인증번호 요청
     */
    public function requestVerificationCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^01[0-9]{8,9}$/',
        ]);

        $phoneNumber = $request->input('phone_number');
        // 6자리 인증번호 생성
        $verificationCode = random_int(100000, 999999);

        // SMS 발송
        $response = $this->smsService->sendVerificationCode($phoneNumber, $verificationCode);
        if ($response['success']) {
            Session::put('verification_code', $verificationCode);
            return response()->json(['message' => '인증번호가 전송되었습니다.', 'code' => $verificationCode]);
        } else {
            return response()->json(['message' => $response['message']], 500);
        }
    }

    /**
     * 인증번호 확인
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:6',
            'phone_number' => 'required|string|regex:/^01[0-9]{8,9}$/',
        ]);

        $inputCode = $request->input('verification_code');
        $sessionCode = Session::get('verification_code');

        if ($inputCode == $sessionCode) {
            Session::forget('verification_code');
            return response()->json(['success' => true, 'message' => '인증이 완료되었습니다.']);
        } else {
            return response()->json(['message' => '인증번호가 잘못되었습니다.', 'code' => $sessionCode], 400);
        }
    }

    /**
     * 인증번호 확인 후 아이디 반환 (아이디 찾기)
     */
    public function verifyCodeAndReturnEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:6',
            'phone_number' => 'required|string|regex:/^01[0-9]{8,9}$/',
        ]);

        $inputCode = $request->input('verification_code');
        $sessionCode = Session::get('verification_code');

        if ($inputCode == $sessionCode) {
            Session::forget('verification_code');
            // 유저 정보 조회
            $user = User::where('phone', $request->phone_number)->first();
            if ($user) {
                return response()->json(['success' => true, 'message' => '인증이 완료되었습니다.', 'email' => $user->email]);
            } else {
                return response()->json(['success' => true, 'message' => '가입된 정보가 없습니다.', 'email' => null]);
            }
        } else {
            return response()->json(['message' => '인증번호가 잘못되었습니다.', 'code' => $sessionCode], 400);
        }
    }

    /**
     * 인증번호 확인 후 아이디 반환 (비밀번호 찾기)
     */
    public function verifyCodeAndReturnPassReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|digits:6',
            'phone_number' => 'required|string|regex:/^01[0-9]{8,9}$/',
        ]);

        $inputCode = $request->input('verification_code');
        $sessionCode = Session::get('verification_code');

        if ($inputCode == $sessionCode) {
            Session::forget('verification_code');
            // 이메일과 핸드폰번호로 유저 정보 조회
            $user = User::where('email', $request->email)->where('phone', $request->phone_number)->first();

            if ($user) {
                Session::put('user_id_to_verify', $user->id);
                return response()->json(['success' => true, 'message' => '인증이 완료되었습니다.', 'email' => $user->email]);
            } else {
                return response()->json(['success' => true, 'message' => '가입된 정보가 없습니다.', 'email' => null]);
            }
        } else {
            return response()->json(['message' => '인증번호가 잘못되었습니다.', 'code' => $sessionCode], 400);
        }
    }



    /**
     * 핸드폰번호 중복 확인
     */
    public function checkPhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|regex:/^01[0-9]{8,9}$/',
        ]);

        $user = User::where('phone', $request->phone_number)->first();

        if ($user) {
            return response()->json([
                'exists' => true,
                'email' => $user->email, // 가입된 이메일 반환
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * 인증번호 삭제
     */
    public function deleteVerificationCode()
    {
        Session::forget('verification_code');
        return response()->json(['message' => '인증번호가 삭제되었습니다.']);
    }

}
