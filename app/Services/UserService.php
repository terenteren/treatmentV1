<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\DB;


class UserService
{

    /**
     * 사용자 정보 업데이트
     *
     * @param int $userId
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function updateUserProfile(int $userId, array $data)
    {
        DB::beginTransaction();

        try {
            // User 모델 업데이트
            $user = User::findOrFail($userId);
            $user->update([
                'name' => $data['name'],
            ]);

            // UserProfile 모델 업데이트
            $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();
            $userProfile->update([
                'sido' => $data['sido'],
                'sigugun' => $data['sigugun'],
            ]);

            DB::commit(); // 트랜잭션 커밋
        } catch (Exception $e) {
            DB::rollBack(); // 트랜잭션 롤백
            throw new Exception('사용자 정보 업데이트 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }


}
