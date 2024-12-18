<?php

namespace App\Http\Controllers;

use App\Models\UserChildProfile;
use App\Services\ChildService;
use App\Services\ChildTreatmentService;
use App\Services\FileValidatorService;
use App\Services\SMSService;
use App\Services\UserAuthService;
use App\Services\UserSignupService;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    private $childService, $fileValidator;

    public function __construct(
        ChildService $childService,
        FileValidatorService $fileValidator
    )
    {
        $this->childService = $childService;
        $this->fileValidator = $fileValidator;
    }

    public function addChild()
    {
        $data = [
            'page_step' => 'menu_con5_on',
        ];

        return view('user.child.add', compact('data'));
    }

    public function addChildStore(Request $request)
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
        $this->childService->saveChildProfile(
            $request->only(['kid_name', 'birth', 'gender', 'disease_name']),
            $request->file('profile_image')
        );

        return redirect('mypage')->with('message', '자녀 정보가 성공적으로 등록되었습니다.');
    }


    public function updateChild($id)
    {
        $child = UserChildProfile::findOrFail($id);

        return view('user.child.update', compact('child'));
    }

    public function updateChildStore(Request $request, $id)
    {
        $validated = $request->validate([
            'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
            'kid_name' => 'required|string|max:50',
            'birth' => 'required|date',
            'gender' => 'required|string|in:남자,여자',
            'disease_name' => 'nullable|string',
        ]);

        $result = $this->childService->updateChildProfile($id, $validated, $request->file('profile_image'));

        if ($result['success']) {
            return redirect()->route('mypage')->with('message', '자녀 정보가 수정되었습니다.');
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    public function showSelectedChild()
    {

        return view('user.child.showAll');
    }

    public function changeSelectedChildStore(Request $request)
    {
        $request->validate([
            'selected_child_id' => 'required|exists:user_child_profiles,id',
        ]);

        // 서비스 클래스를 호출하여 로직 처리
        $this->childService->updateSelectedChild($request->selected_child_id);

        // 성공적으로 처리 후 리다이렉트와 플래시 메시지 전달
        return redirect()->back()->with('popup', true);
    }



}
