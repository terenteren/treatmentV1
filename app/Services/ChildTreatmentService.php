<?php

namespace App\Services;

use App\Models\ChildTreatment;
use App\Models\User;
use App\Models\UserChildProfile;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ChildTreatmentService
{
    /**
     * 유저의 치료 정보 저장
     *
     * @param int $userId
     * @param array $treatmentData
     * @return void
     */
    public function saveTreatments(int $userId, array $treatmentData)
    {
        $kind_etc_arr = $treatmentData['kind_etc'] ?? [];
        $kinds = $treatmentData['kind'] ?? [];
        $clinicNames = $treatmentData['clinic_name'] ?? [];
        $startDates = $treatmentData['start_date'] ?? [];
        $treatmentFrequencies = $treatmentData['treatment_frequency'] ?? [];
        $monthlyCosts = $treatmentData['monthly_cost'] ?? [];

        $treatmentCount = count($kinds);

        // UserChildProfile에서 유저 아이디로 아이 정보 조회 (처음엔 아이가 한명밖에 등록이 안되어 있음)
        $childProfile = UserChildProfile::where('user_id', $userId)->first();

        // 치료 정보 저장
        for ($i = 0; $i < $treatmentCount; $i++) {
            ChildTreatment::create([
                'user_id' => $userId,
                'child_id' => $childProfile->id,
                'kind' => $kinds[$i],
                'kind_etc' => $kind_etc_arr[$i],
                'clinic_name' => $clinicNames[$i],
                'start_date' => $startDates[$i],
                'treatment_frequency' => $treatmentFrequencies[$i],
                'monthly_cost' => $monthlyCosts[$i],
            ]);
        }
    }
}
