<x-layout>

<style>
    .index_head, .index_menu_container { display: none; }
    .date_select { cursor: pointer; }
    .input_title { margin-bottom: 5px; }
    .container_wrap { padding-bottom: 200px; }
</style>

<span class="sub_top">
    <span class="back" onClick="history.go(-1)">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
    <span class="page_name">치료정보기록</span>
</span>

<!-- Form 시작 -->
<form id="treatmentForm" action="{{ route('processSignup4') }}" method="POST">
    @csrf
    <span class="container_wrap sub_container_wrap">
        <span class="container sub_container sub_container1">
            <span class="input_wrap">
                <span class="input_title">현재 <span>{{ $user->childProfiles[0]->kid_name }}</span>가 받고 있는 치료 정보를 모두 기록해주세요</span>
                <span class="input_title_sub">(아직 받는 치료가 없다면 바로 '종료하기'를 눌러주세요)</span>

                <span class="input_container add_input_container">
                    <span class="input_con">
                        <span class="input_tag">치료정보1</span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">치료종류</span>
                        <span class="input_select_wrap input_select_wrap1">
                            <span class="input_select_box input_select_box1">치료종류를 선택해주세요<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                            <span class="input_select_option_wrap input_select_option_wrap1">
                                @foreach(\App\Models\Treatment::all() as $treatment)
                                    <span class="input_select_option input_select_option1
                                        {{ $treatment->name === '기타(직접기록)' ? 'input_select_option_text input_select_option_text1' : '' }}">
                                        {{ $treatment->name }}
                                    </span>
                                @endforeach
                            </span>
                        </span>
                        <span class="input_box input_textbox kind_etc" style="display: none;">
                            <input type="text" name="kind_etc[]" class="kind_etc_val" placeholder="치료종류를 기록해주세요">
                        </span>
                        <span class="input_box input_textbox input_textbox1"><input type="text" name="kind[]" placeholder="치료종류를 기록해주세요"></span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">치료실 이름</span>
                        <span class="input_box"><input type="text" name="clinic_name[]" placeholder="치료실 이름을 기록해주세요"></span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">시작시점</span>
                        <span class="input_date">
                            <span class="input_box"><input type="text" class="date_select" name="start_date[]" placeholder="시작시점" readonly></span>
                            <span class="input_date_img"><img src="{{ asset('img/icon/calendar.svg') }}" alt="달력"></span>
                        </span>
                    </span>

                    <span class="input_con">
                        <span class="input_name">치료 빈도</span>
                        <span class="input_select_wrap input_select_wrap2">
                            <span class="input_select_box input_select_box2">치료 빈도를 선택해주세요<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                            <span class="input_select_option_wrap input_select_option_wrap2">
                                <span class="input_select_option input_select_option2">주 1회</span>
                                <span class="input_select_option input_select_option2">주 2회</span>
                                <span class="input_select_option input_select_option2">주 3회</span>
                                <span class="input_select_option input_select_option2">주 4회</span>
                                <span class="input_select_option input_select_option2">주 5회 이상</span>
                            </span>
                        </span>
                        <input type="hidden" name="treatment_frequency[]">
                    </span>

                    <span class="input_con">
                        <span class="input_name">월 평균 치료 비용 (예, 주 2회 > 월 8회 기준)<span class="input_notice">단위 : 만원</span></span>
                        <span class="input_box"><input type="text" name="monthly_cost[]" placeholder="월 평균 치료 비용을 기록해주세요"></span>
                        <span class="input_detail_sub">(한 달을 4주로 계산. 예: 주 2회면 월 8회 기준비용 입력)</span>
                    </span>
                </span>

            </span>
        </span>
    </span>

    <span class="input_btn_wrap blur two_input_btn_wrap">
        <span class="input_btn add_input_btn">추가기록</span>
        <button type="submit" class="input_btn" style="border: 0;">완료</button>
    </span>
</form>

<!-- 삭제 확인 팝업 -->
<span class="popup del_popup">
    <span class="popup_text">해당 정보를 삭제하시겠습니까?</span>
    <span class="popup_btn_wrap">
        <span class="popup_btn close_popup_btn">취소</span>
        <span class="popup_btn popup_on close_popup_btn">삭제</span>
    </span>
</span>

<!-- 템플릿 -->
<script type="text/template" id="treatment-template">
    <span class="input_container add_input_container">
    <!-- 여기에 기존 치료정보 템플릿 추가 -->
    </span>
</script>

<script>
$(document).ready(function () {
    // 추가기록 버튼 클릭
    let treatmentCounter = 1;

    const treatmentOptions = @json(\App\Models\Treatment::all()->pluck('name'));

    // 추가기록 버튼 클릭
    $('.add_input_btn').click(function () {
        treatmentCounter++;

        // 옵션 목록 생성
        let optionsHtml = '';
        treatmentOptions.forEach((name) => {
            const extraClass = name === '기타(직접기록)' ? 'input_select_option_text input_select_option_text1' : '';
            optionsHtml += `<span class="input_select_option input_select_option1 ${extraClass}">${name}</span>`;
        });

        const newTreatment = `
        <span class="input_container add_input_container">
            <span class="input_con">
                <span class="input_tag">치료정보${treatmentCounter}<span class="input_del"><img src="{{ asset('img/icon/delete.svg') }}" alt="삭제"></span></span>
            </span>
            <span class="input_con">
                <span class="input_name">치료종류</span>
                <span class="input_select_wrap input_select_wrap1">
                    <span class="input_select_box input_select_box1">치료종류를 선택해주세요<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                    <span class="input_select_option_wrap input_select_option_wrap1">
                        ${optionsHtml}
                    </span>
                </span>
                <span class="input_box input_textbox kind_etc" style="display: none;">
                    <input type="text" name="kind_etc[]" class="kind_etc_val" placeholder="치료종류를 기록해주세요">
                </span>
                <span class="input_box input_textbox input_textbox1"><input type="text" id="kind_${treatmentCounter}" name="kind[]" placeholder="치료종류를 기록해주세요"></span>
            </span>
            <span class="input_con">
                <span class="input_name">치료실 이름</span>
                <span class="input_box"><input type="text" id="clinic_name_${treatmentCounter}" name="clinic_name[]" placeholder="치료실 이름을 기록해주세요"></span>
            </span>
            <span class="input_con">
                <span class="input_name">시작시점</span>
                <span class="input_date">
                    <span class="input_box"><input type="text" class="date_select" id="start_date_${treatmentCounter}" name="start_date[]" placeholder="시작시점" readonly></span>
                    <span class="input_date_img"><img src="{{ asset('img/icon/calendar.svg') }}" alt="달력"></span>
                </span>
            </span>
            <span class="input_con">
                <span class="input_name">치료 빈도</span>
                <span class="input_select_wrap input_select_wrap2">
                    <span class="input_select_box input_select_box2">치료 빈도를 선택해주세요<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                    <span class="input_select_option_wrap input_select_option_wrap2">
                        <span class="input_select_option input_select_option2">주 1회</span>
                        <span class="input_select_option input_select_option2">주 2회</span>
                        <span class="input_select_option input_select_option2">주 3회</span>
                        <span class="input_select_option input_select_option2">주 4회</span>
                        <span class="input_select_option input_select_option2">주 5회 이상</span>
                    </span>
                </span>
                <input type="hidden" id="frequency_${treatmentCounter}" name="treatment_frequency[]">
            </span>
            <span class="input_con">
                <span class="input_name">월 평균 치료 비용<span class="input_notice">단위 : 만원</span></span>
                <span class="input_box"><input type="text" id="monthly_cost_${treatmentCounter}" name="monthly_cost[]" placeholder="월 평균 치료 비용을 기록해주세요"></span>
                <span class="input_detail_sub">(한 달을 4주로 계산. 예: 주 2회면 월 8회 기준비용 입력)</span>
            </span>
        </span>`;
        $('.sub_container1 .input_wrap').append(newTreatment);
        initializeDatePickers(); // 추가된 DatePicker 초기화
    });

    // DatePicker 초기화
    function initializeDatePickers() {
        $('.date_select').datepicker({
            onSelect: function (dateText) {
                const hiddenField = $(this).siblings('input[type="hidden"]');
                hiddenField.val(dateText); // 선택된 값을 hidden 필드에 저장
            }
        });
    }

    // 치료종류 선택 이벤트
    $(document).on('click', '.input_select_box1', function () {
        // 옵션을 토글하여 표시/숨김 처리
        $(this).siblings('.input_select_option_wrap1').toggle();
    });

    $(document).on('click', '.input_select_option1', function () {
        const selectedValue = $(this).text().trim();
        const parentWrap = $(this).closest('.input_select_wrap1');

        // 선택된 값 표시
        parentWrap.find('.input_select_box1').text(selectedValue);

        if (selectedValue === '기타(직접기록)') {
            // "기타(직접기록)" 선택 시 kind[] 값 고정
            parentWrap.siblings('.kind_etc').show();
            parentWrap.siblings('.input_textbox1').find('input').val('기타(직접기록)');
        } else {
            // 기타 이외의 선택 시 kind[] 값 설정
            parentWrap.siblings('.kind_etc').hide();
            parentWrap.siblings('.kind_etc').find('input').val('null'); // kind_etc 초기화
            parentWrap.siblings('.input_textbox1').find('input').val(selectedValue);
        }

        // 옵션 목록 숨김
        parentWrap.find('.input_select_option_wrap1').hide();
    });

    $(document).on('input', '.kind_etc_val', function () {
        const inputValue = $(this).val().trim();
        const parentWrap = $(this).closest('.input_con');

        // "기타(직접기록)" 값만 kind_etc에 반영
        parentWrap.find('.kind_etc input').val(inputValue);
    });


    // 페이지 로드 시 옵션 숨김
    $('.input_select_option_wrap1').hide();

    // 치료 빈도 선택 이벤트
    $(document).on('click', '.input_select_box2', function () {
        const options = $(this).siblings('.input_select_option_wrap2');
        options.toggle();
    });

    $(document).on('click', '.input_select_option2', function () {
        const selectedValue = $(this).text();
        const parentBox = $(this).closest('.input_select_wrap2');
        parentBox.find('.input_select_box2').text(selectedValue); // 선택된 값 표시
        parentBox.siblings('input[type="hidden"]').val(selectedValue); // 선택된 값 저장
        $(this).parent().hide(); // 옵션 숨김
    });

    // 삭제 버튼 클릭
    $(document).on('click', '.input_del', function () {
        $(this).closest('.add_input_container').remove();
    });

    // 초기화
    initializeDatePickers();

    // 삭제 확인 팝업
    $('#treatmentForm').submit(function () {
        $('.kind_etc_val').each(function () {
            if ($(this).val().trim() === '') {
                $(this).val('null'); // 비어 있는 경우 "null"로 설정
            }
        });
    });

});

// 로컬스토리지 drugDataList 초기화
localStorage.removeItem('drugDataList');

</script>

</x-layout>
