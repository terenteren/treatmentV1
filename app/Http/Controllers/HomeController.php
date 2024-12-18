<?php

namespace App\Http\Controllers;

use App\Models\DrugRecord;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // selected_child_id에 해당하는 UserChildProfile 조회
        $selectedChildProfile = $user->childProfiles()->find($user->profile->selected_child_id);

        // 받고 있는 치료 갯수 조회
        $disease_name_cnt = 0;
        if ($selectedChildProfile) {
            $disease_name_cnt = substr_count($selectedChildProfile['disease_name'], ',') + 1;
        }

        // 먹고 있는 약 갯수와 약물 기록 조회
        $drugRecords = DrugRecord::with('drug')
            ->where('child_id', $selectedChildProfile['id'])
            ->get();

        $drug_record_cnt = $drugRecords->count();

        $data = [
            'page_step' => 'index_menu_con1_on',
            'selectedChildProfile' => $selectedChildProfile,
            'disease_name_cnt' => $disease_name_cnt,
            'drug_record_cnt' => $drug_record_cnt,
            'drugRecords' => $drugRecords,
        ];

        return view('home', compact('data'));
    }

    public function showDrugRecord($id)
    {
        $drugRecord = DrugRecord::with('drug')->findOrFail($id);

        return view('user.drug_record.show', compact('drugRecord'));
    }

}
