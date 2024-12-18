<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate(); // 토큰 검증 및 사용자 인증
        } catch (TokenExpiredException $e) {
            return $this->unauthenticatedResponse($request, '접속 시간이 만료되었습니다.');
        } catch (JWTException $e) {
            return $this->unauthenticatedResponse($request, '로그인이 필요합니다.');
        }

        return $next($request);
    }

    /**
     * Unauthenticated 응답 처리
     */
    private function unauthenticatedResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 401); // JSON 응답
        }

        return redirect()->route('login')->withErrors(['message' => $message]); // 리다이렉트
    }

}
