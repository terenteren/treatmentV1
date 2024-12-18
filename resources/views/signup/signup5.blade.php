<x-layout>

<span class="sub_top">
    <span class="back" onClick="history.go(-1)">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기"></span>
    <span class="page_name">약물치료정보기록</span>
</span>

<form action="{{ route('processSignup5') }}" method="POST">
    @csrf
    <input type="hidden" name="child_id" value="{{ $user->childProfiles[0]->id }}">
    <span class="container_wrap sub_container_wrap">
        <span class="container sub_container sub_container1">
            <span class="input_wrap" id="drug_info_container">
                <span class="input_title"><span>{{ $user->childProfiles[0]->kid_name }}</span>가 복용 중인 약에 대해 알려주세요</span>
                <span class="input_container add_input_container" data-index="1">
                    <span class="input_con">
                        <span class="input_tag">약물정보1</span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">약물명</span>
                        <span class="input_box drug_search">
                            <input type="text" name="drug_name[]" placeholder="약물명" readonly>
                            <input type="hidden" name="drug_item_seq[]" readonly>
                        </span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">용량</span>
                        <span class="input_option">
                            <span class="input_box">
                                <input type="text" name="dosage[]" placeholder="1회 복용량">
                            </span>
                            <span class="input_option_btn">mg</span>
                        </span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">복용 시작일</span>
                        <span class="input_date">
                            <span class="input_box">
                                <input type="text" name="start_date[]" placeholder="복용 시작일" class="start_date" readonly>
                            </span>
                            <span class="input_date_img">
                                <img src="{{ asset('img/icon/calendar.svg') }}" alt="달력">
                            </span>
                        </span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">복용 시간</span>
                        <span class="input_time">
                            <span class="time">아침</span>
                            <span class="time">점심</span>
                            <span class="time">저녁</span>
                            <span class="time">밤</span>
                        </span>
                        <input type="hidden" name="drug_take_time[]" class="drug_take_time">
                    </span>
                    <span class="input_con">
                        <span class="input_name">복용일<span class="input_notice">*약을 드시는 요일을 선택해주세요</span></span>
                        <span class="input_day">
                            <span class="day">월</span>
                            <span class="day">화</span>
                            <span class="day">수</span>
                            <span class="day">목</span>
                            <span class="day">금</span>
                            <span class="day">토</span>
                            <span class="day">일</span>
                        </span>
                        <input type="hidden" name="drug_take_date[]" class="drug_take_date">
                    </span>
                </span>
            </span>
        </span>
    </span>

    <span class="input_btn_wrap blur two_input_btn_wrap">
        <span class="input_btn add_input_btn">추가기록</span>
        <button type="submit" class="input_btn signup_input_btn" style="border: 0;">회원가입</button>
    </span>
</form>
<script>
let index = 1;

// 복용 시간 선택
$(document).on('click', '.time', function () {
    $(this).toggleClass('time_on');
    const selectedTimes = $(this).closest('.input_con').find('.time_on').map(function () {
        return $(this).text();
    }).get().join(',');
    $(this).closest('.input_con').find('.drug_take_time').val(selectedTimes);
});

// 복용일 선택
$(document).on('click', '.day', function () {
    $(this).toggleClass('day_on');
    const selectedDays = $(this).closest('.input_con').find('.day_on').map(function () {
        return $(this).text();
    }).get().join(',');
    $(this).closest('.input_con').find('.drug_take_date').val(selectedDays);
});

// 페이지 로드 시 기존 로컬스토리지 데이터를 불러와 표시
$(document).ready(function () {
    const drugDataList = JSON.parse(localStorage.getItem('drugDataList')) || [];

    drugDataList.forEach((drug, i) => {
        if (i > 0) {
            // 첫 번째 약물 정보는 이미 있으므로 나머지는 추가
            $('.add_input_btn').trigger('click');
        }

        const inputContainer = $(`.add_input_container[data-index="${i + 1}"]`);
        inputContainer.find('input[name="drug_name[]"]').val(drug.item_name);
        inputContainer.find('input[name="drug_item_seq[]"]').val(drug.item_seq);
        inputContainer.find('input[name="dosage[]"]').val(drug.dosage);
        inputContainer.find('input[name="start_date[]"]').val(drug.start_date);
        inputContainer.find('input[name="drug_take_time[]"]').val(drug.drug_take_time);
        inputContainer.find('input[name="drug_take_date[]"]').val(drug.drug_take_date);

        // 복용 시간에 따른 클래스 추가
        if (drug.drug_take_time) {
            const times = drug.drug_take_time.split(','); // 복용 시간을 쉼표로 구분
            times.forEach((time) => {
                inputContainer.find(`.time:contains('${time}')`).addClass('time_on');
            });
        }

        // 복용일에 따른 클래스 추가
        if (drug.drug_take_date) {
            const days = drug.drug_take_date.split(','); // 복용일을 쉼표로 구분
            days.forEach((day) => {
                inputContainer.find(`.day:contains('${day}')`).addClass('day_on');
            });
        }
    });
});



// 약물명 검색 버튼 클릭 시 drugSearch 페이지로 이동
$(document).on('click', '.drug_search', function () {
    const currentIndex = $(this).closest('.add_input_container').data('index');
    localStorage.setItem('currentDrugIndex', currentIndex); // 현재 약물의 인덱스 저장
    window.location.href = "{{ route('drugSearch') }}";
});

// 추가기록 버튼 클릭
$('.add_input_btn').click(function () {
    index++;
    const newInputContainer = `
    <span class="input_container add_input_container" data-index="${index}">
        <span class="input_con">
            <span class="input_tag">약물정보${index}
                <span class="input_del">
                    <img src="{{ asset('img/icon/delete.svg') }}" alt="삭제">
                </span>
            </span>
        </span>
        <span class="input_con">
            <span class="input_name">약물명</span>
            <span class="input_box drug_search">
                <input type="text" name="drug_name[]" placeholder="약물명" readonly>
                <input type="hidden" name="drug_item_seq[]" readonly>
            </span>
        </span>
        <span class="input_con">
            <span class="input_name">용량</span>
            <span class="input_option">
                <span class="input_box">
                    <input type="text" name="dosage[]" placeholder="1회 복용량">
                </span>
                <span class="input_option_btn">mg</span>
            </span>
        </span>
        <span class="input_con">
            <span class="input_name">복용 시작일</span>
            <span class="input_date">
                <span class="input_box">
                    <input type="text" name="start_date[]" placeholder="복용 시작일" class="start_date" readonly>
                </span>
                <span class="input_date_img">
                    <img src="{{ asset('img/icon/calendar.svg') }}" alt="달력">
                </span>
            </span>
        </span>
        <span class="input_con">
            <span class="input_name">복용 시간</span>
            <span class="input_time">
                <span class="time">아침</span>
                <span class="time">점심</span>
                <span class="time">저녁</span>
                <span class="time">밤</span>
            </span>
            <input type="hidden" name="drug_take_time[]" class="drug_take_time">
        </span>
        <span class="input_con">
            <span class="input_name">복용일<span class="input_notice">*약을 드시는 요일을 선택해주세요</span></span>
            <span class="input_day">
                <span class="day">월</span>
                <span class="day">화</span>
                <span class="day">수</span>
                <span class="day">목</span>
                <span class="day">금</span>
                <span class="day">토</span>
                <span class="day">일</span>
            </span>
            <input type="hidden" name="drug_take_date[]" class="drug_take_date">
        </span>
    </span>`;
    $('#drug_info_container').append(newInputContainer);
    $('.start_date').datepicker(); // datepicker 초기화
});

// 삭제 버튼 클릭 이벤트
$(document).on('click', '.input_del', function () {
    if (confirm('이 항목을 삭제하시겠습니까?')) {
        $(this).closest('.add_input_container').remove();
    }
});


// 페이지를 떠날 때 로컬스토리지에 데이터를 저장
$(window).on('beforeunload', function () {
    const drugDataList = [];
    $('.add_input_container').each(function () {
        const itemName = $(this).find('input[name="drug_name[]"]').val();
        const itemSeq = $(this).find('input[name="drug_item_seq[]"]').val();
        const dosage = $(this).find('input[name="dosage[]"]').val();
        const startDate = $(this).find('input[name="start_date[]"]').val();
        const takeTime = $(this).find('input[name="drug_take_time[]"]').val();
        const takeDate = $(this).find('input[name="drug_take_date[]"]').val();

        if (itemName) {
            drugDataList.push({
                item_name: itemName,
                item_seq: itemSeq,
                dosage: dosage,
                start_date: startDate,
                drug_take_time: takeTime,
                drug_take_date: takeDate
            });
        }
    });
    localStorage.setItem('drugDataList', JSON.stringify(drugDataList), 86400000); // 24시간 저장
});


</script>

</x-layout>
