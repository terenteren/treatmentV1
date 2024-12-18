<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ChildTreatmentService;
use App\Services\FileValidatorService;
use App\Services\SMSService;
use App\Services\UserAuthService;
use App\Services\UserSignupService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    private $smsService, $signupService, $authService, $childTreatmentService, $fileValidator;

    public function __construct(
        SMSService $smsService,
        UserSignupService $signupService,
        UserAuthService $authService,
        ChildTreatmentService $childTreatmentService,
        FileValidatorService $fileValidator
    )
    {
        $this->smsService = $smsService;
        $this->signupService = $signupService;
        $this->authService = $authService;
        $this->childTreatmentService = $childTreatmentService;
        $this->fileValidator = $fileValidator;
    }

    /**
     * 세션에 필수 데이터가 있는지 확인하고 없으면 리다이렉트
     */
    protected function ensureSignupDataExists()
    {
        $signupData = Session::get('signup', []);

        // 필수 데이터가 누락된 경우 리다이렉트
        if (empty($signupData['email']) || empty($signupData['name']) || empty($signupData['phone'])) {
            return Redirect::route('signup1')->withErrors(['message' => '필수 정보가 누락되었습니다.']);
        }

        return $signupData;
    }

    /**
     * 회원가입 휴대폰 인증번호 발송
     */
    public function sendVerificationCode(Request $request)
    {
        $phone = $request->input('phone');
        $callback = '0317281281'; // 발신번호 (사전에 등록된 번호 사용)
        $message = '인증 코드: ' . rand(100000, 999999); // 임의의 인증 코드 생성

        $result = $this->smsService->sendSMS($phone, $callback, $message);

        return response()->json(['result' => $result]);
    }

    public function showSignup1()
    {
        return view('signup.signup1');
    }

    public function processSignup1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string|max:50',
            'phone_number' => 'required|string|regex:/^01[0-9]{8,9}$/',
            'sido' => 'nullable|string',
            'sigugun' => 'nullable|string',
        ], [
            'email.required' => '이메일은 필수 입력 항목입니다.',
            'email.email' => '유효한 이메일 주소를 입력해주세요.',
            'phone.regex' => '핸드폰 번호는 01012345678 형식이어야 합니다.',
        ]);

        // 세션에 데이터 저장
        Session::put('signup.email', $request->email);
        Session::put('signup.name', $request->name);
        Session::put('signup.phone', $request->phone_number);
        Session::put('signup.sido', $request->sido);
        Session::put('signup.sigugun', $request->sigugun);

        return redirect()->route('signup2');
    }

    public function showSignup2()
    {
        // 세션 데이터 확인
        $signupData = $this->ensureSignupDataExists();
        if ($signupData instanceof RedirectResponse) {
            return $signupData;
        }

        return view('signup.signup2', compact('signupData'));
    }

    public function processSignup2(Request $request)
    {
        // 세션 데이터 확인
        $signupData = $this->ensureSignupDataExists();
        if ($signupData instanceof RedirectResponse) {
            return $signupData;
        }

        // 비밀번호 유효성 검사
        $request->validate([
            'password' => 'required|string|min:6',
            'password_confirm' => 'required|string|same:password',
        ]);

        // 서비스 클래스 호출하여 사용자 생성
        $user = $this->signupService->registerUser($signupData, $request->password);

        // 세션 초기화
        $this->authService->clearSignupSession();

        // $token = $this->authService->loginUserWithJWT($user); // JWT 토큰 생성
        $this->authService->loginUser($user);

        return redirect('/signup3')->with('message', '회원가입이 완료되었습니다.');
    }

    public function showSignup3()
    {
        return view('signup.signup3');
    }

    public function processSignup3(Request $request)
    {
        $validated = $request->validate([
            'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,application/octet-stream|max:10240', // 최대 10MB 허용
            'kid_name' => 'required|string|max:50',
            'birth' => 'required|date',
            'gender' => 'required|string|in:남자,여자',
            'disease_name' => 'nullable|string',
        ]);

        // 업로드된 파일 확인
        if ($request->hasFile('profile_image')) {
            $imageFile = $request->file('profile_image');

            // 파일 크기 확인
            if (!$this->fileValidator->validateFileSize($imageFile, 10)) {
                return response()->json([
                    'popup' => '<span class="popup send_popup_fail">
                                <span class="popup_text">이미지의 용량이 너무 큽니다.(최대 10MB)</span>
                                <span class="popup_btn_wrap full_popup_btn_wrap">
                                    <span class="popup_btn popup_on close_popup_btn">확인</span>
                                </span>
                            </span>'
                ], 400);
            }
        }

        // 자녀 프로필 저장
        $this->signupService->saveChildProfile(
            $request->only(['kid_name', 'birth', 'gender', 'disease_name']),
            $request->file('profile_image')
        );

        return redirect('signup4')->with('message', '자녀 정보가 성공적으로 등록되었습니다.');
    }


    public function showSignup4()
    {
        $user = auth()->user();

        return view('signup.signup4', compact('user'));
    }

    public function processSignup4(Request $request)
    {
        $user = auth()->user();

        $this->childTreatmentService->saveTreatments($user->id, $request->all());

        return redirect('signup5')->with('message', '치료 정보가 성공적으로 저장되었습니다.');
    }

    public function showSignup5()
    {
        // 어떤 약물들을 불러오나? 아무 약물이나 다 불러올순 없는ㄷ...
        $user = auth()->user();
        return view('signup.signup5', compact('user'));
    }



}
