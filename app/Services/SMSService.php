<?php

// app/Services/SMSService.php

namespace App\Services;

use App\Services\SMS;
use Illuminate\Support\Facades\Config;

class SMSService
{
    protected $sms;

    public function __construct()
    {
        $this->sms = new SMS();
    }

    public function sendVerificationCode($phoneNumber, $verificationCode)
    {
        $strTelList = [$phoneNumber];
        $strCallBack = Config::get('services.icode.callback_number');
        $strData = "서울대병원 치료모니터링 \n인증번호는 $verificationCode 입니다.";

        // SMS 발송 설정
        $result = $this->sms->Add($strTelList, $strCallBack, $strData);

        // 전송 시도 및 결과 처리
        if ($result) {
            $sendResult = $this->sms->Send();
            if ($sendResult) {
                return ['success' => true, 'message' => '인증번호 전송 성공'];
            } else {
                return ['success' => false, 'message' => 'SMS 서버와 통신이 불안정합니다.'];
            }
        } else {
            return ['success' => false, 'message' => '인증번호 생성에 실패했습니다.'];
        }
    }
}
