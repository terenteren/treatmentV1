<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        // users 테이블에서 이메일 중복 여부 확인
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function mypage()
    {
        $user = auth()->user();

        // selected_child_id에 해당하는 UserChildProfile 조회
        $selectedChildProfile = $user->childProfiles()->find($user->profile->selected_child_id);

        $data = [
            'page_step' => 'menu_con5_on',
            'childProfiles' => $user->childProfiles, // 자녀 프로필 데이터
            'selectedChildProfile' => $selectedChildProfile, // 선택된 자녀 프로필 데이터
        ];

        return view('user.mypage', compact('data'));
    }

    public function editMyPage()
    {
        $user = auth()->user();

        $data = [
            'page_step' => 'menu_con5_on',
            'user' => $user,
        ];

        return view('user.edit', compact('data'));
    }

    public function editMyPageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sido' => 'required|string',
            'sigugun' => 'required|string',
        ]);

        try {
            // 비즈니스 로직 호출
            $this->userService->updateUserProfile(auth()->user()->id, $request->only(['name', 'sido', 'sigugun']));

            return redirect()->route('editMyPage')->with('popup', true);
        } catch (Exception $e) {
            // 예외 발생 시 다시 돌아가 에러 메시지 표시
            return redirect()->back()->withErrors(['error' => '정보 수정 중 오류가 발생했습니다. 다시 시도해주세요.']);
        }
    }


}
