<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AccountRecoveryService
{
    public function resetPassword($password)
    {
        // 세션에서 사용자 ID 가져오기
        $userId = Session::get('user_id_to_verify');

        if (!$userId) {
            throw new Exception('비밀번호 재설정 세션이 만료되었습니다.');
        }

        // 사용자 조회
        $user = User::find($userId);

        if (!$user) {
            throw new Exception('사용자를 찾을 수 없습니다.');
        }

        // 비밀번호 업데이트
        $user->password = Hash::make($password);
        $user->save();

        // 세션 데이터 삭제
        Session::forget('user_id_to_verify');
    }

}
