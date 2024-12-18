<?php

namespace App\Services;

use App\Models\ChildTreatment;
use App\Models\User;
use App\Models\UserChildProfile;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ChildService
{

    /**
     * 자녀 프로필 데이터를 저장
     */
    public function saveChildProfile(array $data, $profileImage = null)
    {
        $profileImagePath = null;
        $profileImageOriginalName = null;

        if ($profileImage) {
            $profileImageOriginalName = $profileImage->getClientOriginalName(); // 원본 파일명
            $fileName = uniqid('user_' . auth()->id() . '_child_', true) . '.' . $profileImage->getClientOriginalExtension();

            // 디렉토리 확인 및 생성
            if (!Storage::disk('public')->exists('uploads/child_profiles')) {
                Storage::disk('public')->makeDirectory('uploads/child_profiles');
            }

            $profileImagePath = $profileImage->storeAs('uploads/child_profiles', $fileName, 'public'); // 저장
        }

        // UserChildProfile 생성
        return UserChildProfile::create([
            'user_id' => auth()->id(),
            'profile_image' => $profileImagePath,
            'profile_image_origin_name' => $profileImageOriginalName,
            'kid_name' => $data['kid_name'],
            'birth' => $data['birth'],
            'gender' => $data['gender'],
            'disease_name' => $data['disease_name'] ?? null,
        ]);
    }

    /**
     * 자녀 프로필 데이터를 업데이트
     */
    public function updateChildProfile($id, array $data, $profileImage = null)
    {
        try {
            $child = UserChildProfile::findOrFail($id);

            // 프로필 이미지 처리
            if ($profileImage) {
                $fileName = uniqid('user_' . auth()->id() . '_child_', true) . '.' . $profileImage->getClientOriginalExtension();

                // 기존 이미지 삭제
                if ($child->profile_image && Storage::disk('public')->exists($child->profile_image)) {
                    Storage::disk('public')->delete($child->profile_image);
                }

                // 새 이미지 저장
                $child->profile_image = $profileImage->storeAs('uploads/child_profiles', $fileName, 'public');
            }

            // 데이터 업데이트
            $child->update([
                'kid_name' => $data['kid_name'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'disease_name' => $data['disease_name'] ?? null,
            ]);

            return [
                'success' => true,
                'message' => '자녀 프로필이 성공적으로 업데이트되었습니다.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => '자녀 프로필 업데이트 중 오류가 발생했습니다: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * 대표 자녀 선택 업데이트
     *
     * @param int $userId
     * @param int $selectedChildId
     * @return void
     * @throws \Exception
     */
    public function updateSelectedChild(int $selectedChildId)
    {
        DB::beginTransaction();

        try {
            // UserProfile 조회
            $userProfile = UserProfile::where('user_id', auth()->id())->firstOrFail();

            // selected_child_id 업데이트
            $userProfile->selected_child_id = $selectedChildId;
            $userProfile->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('대표 자녀 업데이트 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }


}
