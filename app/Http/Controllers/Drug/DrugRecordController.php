<?php
namespace App\Http\Controllers\Drug;

use App\Http\Controllers\Controller;
use App\Models\DrugRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrugRecordController extends Controller
{

    // 약물 기록 조회
    public function index()
    {
        $userId = Auth::id();

        $drugRecords = DrugRecord::where('user_id', $userId)
            ->with('user')
            ->get();

        return view('signup5', compact('drugRecords'));
    }

    // 약물 기록 저장
    public function processSignup5(Request $request)
    {
        $userId = Auth::id(); // 현재 로그인된 사용자 ID

        // 입력된 데이터 가져오기
        $data = $request->all();

        // 기존 데이터 삭제 후 새로 저장
        DrugRecord::where('user_id', $userId)->delete();

        foreach ($data['drug_item_seq'] as $index => $drugItemSeq) {
            if (!$drugItemSeq) {
                continue; // drug_item_seq가 비어있는 경우 건너뜀
            }

            DrugRecord::create([
                'user_id' => $userId,
                'child_id' => $data['child_id'],
                'drug_item_seq' => $drugItemSeq,
                'dosage' => $data['dosage'][$index] ?? null,
                'start_date' => $data['start_date'][$index] ?? null,
                'drug_take_time' => $data['drug_take_time'][$index] ?? null,
                'drug_take_date' => $data['drug_take_date'][$index] ?? null,
            ]);
        }

        return redirect('/')->with('success', '약물 정보가 저장되었습니다.');
    }

}
