<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserAuthService
{

    /**
     * 세션 초기화
     */
    public function clearSignupSession(): void
    {
        Session::forget('signup');
    }

    /**
     * 로그인 처리
     */
    public function loginUser(User $user): void
    {
        Auth::login($user);
    }

    /**
     * JWT 토큰으로 로그인 처리
     */
    public function loginUserWithJWT(User $user)
    {
        // JWT 토큰 생성
        return JWTAuth::fromUser($user);
    }
}
