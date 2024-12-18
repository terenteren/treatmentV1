<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ChildTreatment;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (!auth()->attempt($credentials, $remember)) {
            return redirect()->back()->withErrors(['loginError' => 'Invalid credentials']);
        }

        $user = auth()->user();

        // UserProfile과 ChildTreatment에서 user_id 확인
        $hasUserProfile = UserProfile::where('user_id', $user->id)->exists();
        $hasChildTreatment = ChildTreatment::where('user_id', $user->id)->exists();

        if (!$hasUserProfile || !$hasChildTreatment) {
            return redirect('/signup3');
        }

        // 모두 존재하면 메인 페이지로 이동
        return redirect('/');
    }

    public function logout()
    {
        auth()->logout(); // 세션 종료

        // 로그아웃 후 홈('/')으로 리디렉트
        return redirect('/');
    }

    /**
     * JWT 사용시 적용
     */
    public function _loginStore(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                // 실패 시 에러 메시지를 세션에 저장하고 리디렉트
                return redirect()->back()->withErrors(['loginError' => 'Invalid credentials']);
            }
        } catch (JWTException $e) {
            // 서버 에러 시 메시지를 세션에 저장하고 리디렉트
            return redirect()->back()->withErrors(['loginError' => 'Could not create token']);
        }

        // 토큰이 생성되면 세션에 저장하고, 홈('/')으로 리디렉트
        session(['jwt_token' => $token]);

        return redirect('/');
    }

    /**
     * JWT 사용시 적용
     */
    public function _logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * JWT 사용시 토큰 갱신
     */
    public function refresh()
    {
        $newToken = auth()->refresh();
        return response()->json(['token' => $newToken]);
    }

}
