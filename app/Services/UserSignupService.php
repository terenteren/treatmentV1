<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserChildProfile;
use App\Models\UserProfile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserSignupService
{
    /**
     * 회원가입 처리
     */
    public function registerUser(array $signupData, string $password): User
    {
        return DB::transaction(function () use ($signupData, $password) {
            // User 생성
            $user = User::create([
                'email' => $signupData['email'],
                'name' => $signupData['name'],
                'phone' => $signupData['phone'],
                'password' => Hash::make($password),
            ]);

            // UserProfile 생성
            $user->profile()->create([
                'sido' => $signupData['sido'] ?? null,
                'sigugun' => $signupData['sigugun'] ?? null,
            ]);

            return $user;
        });
    }

    /**
     * 사용자 프로필 데이터를 저장
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $profileImage
     * @return UserProfile
     */
    public function saveUserProfile(array $data, $profileImage = null): UserProfile
    {
        $profileImagePath = null;
        $profileImageOriginalName = null;

        // 프로필 이미지 처리
        if ($profileImage) {
            $profileImageOriginalName = $profileImage->getClientOriginalName(); // 원본 파일명
            $fileName = 'user_' . auth()->id() . '_' . time() . '.' . $profileImage->getClientOriginalExtension();

            // 디렉토리 확인 및 생성
            if (!Storage::disk('public')->exists('uploads/profile_images')) {
                Storage::disk('public')->makeDirectory('uploads/profile_images');
            }

            $profileImagePath = $profileImage->storeAs('uploads/profile_images', $fileName, 'public'); // 저장
        }

        // 현재 로그인한 사용자의 UserProfile 조회
        $userProfile = UserProfile::where('user_id', auth()->id())->first();

        // UserProfile 업데이트 (존재하지 않을 경우 새로 생성)
        if ($userProfile) {
            $userProfile->update([
                'profile_image' => $profileImagePath ?? $userProfile->profile_image, // 새로운 이미지가 없으면 기존 이미지 유지
                'profile_image_origin_name' => $profileImageOriginalName ?? $userProfile->profile_image_origin_name,
                'kid_name' => $data['kid_name'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'disease_name' => $data['disease_name'] ?? $userProfile->disease_name,
            ]);
        } else {
            $userProfile = UserProfile::create([
                'user_id' => auth()->id(),
                'profile_image' => $profileImagePath,
                'profile_image_origin_name' => $profileImageOriginalName,
                'kid_name' => $data['kid_name'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'disease_name' => $data['disease_name'] ?? null,
            ]);
        }

        return $userProfile;
    }

    /**
     * 자녀 프로필 데이터를 저장
     */
    public function saveChildProfile(array $data, $profileImage = null)
    {
        DB::beginTransaction(); // 트랜잭션 시작

        try {
            $profileImagePath = null;
            $profileImageOriginalName = null;

            if ($profileImage) {
                $profileImageOriginalName = $profileImage->getClientOriginalName(); // 원본 파일명
                $fileName = 'user_' . auth()->id() . '_child_' . time() . '.' . $profileImage->getClientOriginalExtension();

                // 디렉토리 확인 및 생성
                if (!Storage::disk('public')->exists('uploads/child_profiles')) {
                    Storage::disk('public')->makeDirectory('uploads/child_profiles');
                }

                $profileImagePath = $profileImage->storeAs('uploads/child_profiles', $fileName, 'public'); // 저장
            }

            // UserChildProfile 생성
            $childProfile = UserChildProfile::create([
                'user_id' => auth()->id(),
                'profile_image' => $profileImagePath,
                'profile_image_origin_name' => $profileImageOriginalName,
                'kid_name' => $data['kid_name'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'disease_name' => $data['disease_name'] ?? null,
            ]);

            // UserProfile 테이블 업데이트
            UserProfile::where('user_id', auth()->id())
                ->update(['selected_child_id' => $childProfile->id]);

            DB::commit(); // 트랜잭션 커밋

            return $childProfile;
        } catch (Exception $e) {
            DB::rollBack(); // 트랜잭션 롤백

            // 예외를 다시 던져 호출 측에서 처리할 수 있게 함
            throw new Exception('자녀 프로필 저장 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }



}
