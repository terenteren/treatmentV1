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


class FileValidatorService
{
    /**
     * 파일 크기 유효성 검사
     */
    public function validateFileSize($file, $maxSizeInMB = 10)
    {
        if ($file->getSize() > $maxSizeInMB * 1024 * 1024) {
            return false; // 파일 크기가 초과된 경우 false 반환
        }
        return true; // 파일 크기가 유효한 경우 true 반환
    }
}
