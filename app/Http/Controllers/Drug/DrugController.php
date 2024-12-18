<?php

namespace App\Http\Controllers\Drug;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function drugSearch()
    {
        $drugs = Drug::orderBy('id', 'asc')->take(200)->get();
        return view('drug.drug_search', compact('drugs'));
    }

    public function drugSearchProc(Request $request)
    {
        $itemName = $request->query('item_name');

        if (!$itemName) {
            $drugs = Drug::orderBy('id', 'asc')->limit(200)->get();
            return response()->json([
                'totalCount' => $drugs->count(),
                'items' => $drugs
            ]);
        }

        // 특수문자 제거
        $cleanedName = preg_replace('/[^A-Za-z0-9가-힣 ]/', '', $itemName);

        // 대소문자 및 부분 검색
        $query = Drug::where('item_name', 'like', "%{$cleanedName}%")->limit(200);

        $drugs = $query->get();

        return response()->json([
            'totalCount' => $drugs->count(),
            'items' => $drugs
        ]);
    }


//    public function drugSearchProc(Request $request)
//    {
//        $itemName = $request->get('item_name');
//
//        if (!$itemName) {
//            return response()->json(['error' => '약물명을 입력해주세요.'], 400);
//        }
//
//        $url = 'http://apis.data.go.kr/1471000/MdcinGrnIdntfcInfoService01/getMdcinGrnIdntfcInfoList01';
//        $queryParams = [
//            'serviceKey' => urldecode('k7NJDleLMcoS5X2LwLFenviz2ik3PXoejNBZGxHcpqlc3QeqfqgnwMrzdkr3FNHIWYjueS%2F0wNn1GnG%2BV%2F5SVA%3D%3D'),
//            'item_name' => $itemName,
//            'type' => 'xml', // XML 포맷 요청
//            'pageNo' => 1,
//            'numOfRows' => 3,
//        ];
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($queryParams));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, false);
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        if (!$response) {
//            return response()->json(['error' => 'API 요청에 실패했습니다.'], 500);
//        }
//
//        // XML 데이터를 SimpleXMLElement로 파싱
//        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
//        if (!$xml) {
//            return response()->json(['error' => 'API 응답을 처리할 수 없습니다.'], 500);
//        }
//
//        // JSON 변환
//        $json = json_decode(json_encode($xml), true);
//
//        // 필요한 데이터만 추출
//        $items = $json['body']['items']['item'] ?? [];
//        if (isset($items['ITEM_SEQ'])) { // 단일 아이템 처리
//            $items = [$items];
//        }
//
//        $totalCount = $json['body']['totalCount'] ?? 0;
//
//        return response()->json([
//            'totalCount' => $totalCount,
//            'items' => $items,
//        ]);
//    }

//    public function storeDrugData()
//    {
//        // 스크립트 실행 시간을 무제한으로 설정
//        set_time_limit(0);
//
//        // CSV 파일 경로
//        $filePath = public_path('uploads/files/dugs_data_20241127.csv');
//
//        // CSV 파일이 존재하는지 확인
//        if (!file_exists($filePath)) {
//            return response()->json(['message' => 'CSV file not found'], 404);
//        }
//
//        // CSV 파일 읽기 및 DB 저장
//        $this->storeDrugsFromCSV($filePath);
//
//        return response()->json(['message' => 'Drug data stored successfully']);
//    }
//
//    private function storeDrugsFromCSV($filePath)
//    {
//        // CSV 파일을 열기
//        $file = fopen($filePath, 'r');
//
//        // 첫 번째 행(헤더)을 건너뛰기
//        fgetcsv($file);
//
//        // 각 행을 처리
//        while (($data = fgetcsv($file)) !== false) {
//
//            // CSV가 EUC-KR로 인코딩된 경우 UTF-8로 변환
//            $data = array_map(function ($field) {
//                return mb_convert_encoding($field, 'UTF-8', 'EUC-KR');
//            }, $data);
//
//            // 필요한 데이터 열 추출
//            $itemSeq = $data[1] ?? null; // B 라인
//            $itemName = $data[2] ?? null; // C 라인
//            $entpName = $data[4] ?? null; // E 라인
//            $chart = $data[5] ?? null; // F 라인
//            $itemImage = $data[6] ?? null; // G 라인
//            $className = $data[19] ?? null; // T 라인
//
//            // 데이터베이스에 저장
//            if ($itemSeq) {
//                Drug::updateOrCreate(
//                    ['item_seq' => $itemSeq], // 조건
//                    [
//                        'item_name' => $itemName,
//                        'entp_name' => $entpName,
//                        'chart' => $chart,
//                        'item_image' => $itemImage,
//                        'class_name' => $className,
//                    ]
//                );
//            }
//        }
//
//        // 파일 닫기
//        fclose($file);
//    }


}
