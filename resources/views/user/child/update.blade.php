<x-layout>

<style>
    /*고정*/
    .index_head, .index_menu_container{display: none;}
    #start_date{cursor: pointer;}
</style>

<span class="sub_top">
	<span class="back" onClick="location.href='/mypage'">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
	<span class="page_name">자녀정보기입</span>
</span>

<form id="signupForm" action="{{ route('updateChildStore', ['id' => $child->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <span class="container_wrap sub_container_wrap">
        <span class="container sub_container sub_container1">
            <span class="input_wrap">
                <span class="input_title">자녀에 대해 알려주세요</span>
                <span class="input_container">
                    <!-- 프로필 사진 -->
                    <span class="input_con">
                        <span class="input_name">프로필사진</span>
                        <span class="input_imgbox_wrap">
                            <span class="input_imgbox_view">
                                <img id="profile_preview" src="{{ asset('storage/'. $child->profile_image) }}" alt="프로필사진" style="{{ $child->profile_image ? '' : 'display: none;' }}">
                            </span>
                            <span class="input_imgbox">
                                <input type="file" id="profile_image" name="profile_image">
                            </span>
                        </span>

                    </span>
                    <!-- 이름 -->
                    <span class="input_con">
                        <span class="input_name">이름</span>
                        <span class="input_box">
                            <input type="text" name="kid_name" value="{{ $child->kid_name }}" placeholder="이름을 입력해주세요">
                        </span>
                    </span>
                    <!-- 생년월일 -->
                    <span class="input_con">
                        <span class="input_name">생년월일</span>
                        <span class="input_date">
                            <span class="input_box">
                                <input type="text" placeholder="생년월일" id="start_date" value="{{ $child->birth }}" readonly>
                                <input type="hidden" name="birth" value="{{ $child->birth }}">
                            </span>
                            <span class="input_date_img">
                                <img src="{{ asset('img/icon/calendar.svg') }}" alt="달력">
                            </span>
                        </span>
                    </span>
                    <!-- 성별 -->
                    <span class="input_con">
                        <span class="input_name">성별</span>
                        <span class="input_tab_wrap">
                            <span class="input_tab gender_tab {{ $child->gender == '남자' ? 'input_type_tab_on selected' : '' }}">남자</span>
                            <span class="input_tab gender_tab {{ $child->gender == '여자' ? 'input_type_tab_on selected' : '' }}">여자</span>
                        </span>
                        <input type="hidden" name="gender" id="gender" value="{{ $child->gender }}">
                    </span>
                    <!-- 진단명 -->
                    <span class="input_con">
                        <span class="input_name">진단명 (복수 선택 가능)</span>
                        <span class="input_type_tab_wrap">
                            @php
                                $diseaseNames = explode(',', $child->disease_name ?? ''); // 기존 값 ','로 분리
                            @endphp
                            <span class="input_type_tab input_type_tab_clear {{ empty($child->disease_name) ? 'input_type_tab_on selected' : '' }}">
                                진단없음
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('언어지연/장애', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                언어지연/장애
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('자폐성장애', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                자폐성장애
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('지적장애', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                지적장애
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('ADHD', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                ADHD
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('경계선지능', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                경계선지능
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('뇌병변', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                뇌병변
                            </span>
                            <span class="input_type_tab disease_tab {{ in_array('뇌전증', $diseaseNames) ? 'input_type_tab_on selected' : '' }}">
                                뇌전증
                            </span>
                            <span class="input_type_tab input_type_tab_text">
                                직접입력
                            </span>
                        </span>
                        <span class="input_type_text">
                            <input type="text" placeholder="진단명을 입력해주세요" value="{{ implode(',', array_diff($diseaseNames, ['언어지연/장애', '자폐성장애', '지적장애', 'ADHD', '경계선지능', '뇌병변', '뇌전증'])) }}">
                        </span>
                        <input type="hidden" name="disease_name" id="disease_name" value="{{ $child->disease_name }}">
                    </span>

                </span>
            </span>
        </span>
    </span>
    <span class="input_btn_wrap blur">
        <button type="submit" class="input_btn" style="border: 0;">자녀 수정하기</button>
    </span>
</form>


<style>
.input_type_tab_on {
    color: #fff;
    background-color: #01d3d5;
    font-weight: 700;
}
</style>

<script>
$(document).ready(function() {
    // 세션스토리지에 삭제
    sessionStorage.removeItem("profile_image");

    // 생년월일 입력
    $("#start_date").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText) {
            $("input[name='birth']").val(dateText);
        }
    });

    // 프로필 사진 업로드 및 미리보기
    const profilePreview = $("#profile_preview");
    const cameraImg = $(".camera_img");
    const profileInput = $("#profile_image");

    profileInput.on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                sessionStorage.setItem("profile_image", e.target.result); // 세션스토리지에 저장
                profilePreview.attr("src", e.target.result); // 미리보기 이미지 설정
                profilePreview.show(); // 업로드된 이미지 표시
                cameraImg.hide(); // 기본 카메라 이미지 숨김
            };
            reader.readAsDataURL(file);
        } else {
            // 파일이 없을 경우 초기 상태로 복구
            sessionStorage.removeItem("profile_image");
            profilePreview.hide();
            cameraImg.show();
        }
    });

    // 초기 상태 설정: 세션스토리지에 이미지가 있으면 로드
    // const storedImage = sessionStorage.getItem("profile_image");
    // if (storedImage) {
    //     profilePreview.attr("src", storedImage).show();
    //     cameraImg.hide();
    // } else {
    //     profilePreview.hide();
    //     cameraImg.show();
    // }

    // 이전 프로필 사진 제거 시 새로 저장
    profileInput.on("click", function () {
        sessionStorage.removeItem("profile_image"); // 기존 프로필 사진 제거
    });

    // 성별 선택 처리
    $(".gender_tab").click(function () {
        $(".gender_tab").removeClass("selected"); // 기존 선택 제거
        $(this).addClass("selected"); // 현재 선택
        $("#gender").val($(this).text()); // 성별 히든 필드 업데이트
    });

    // 진단명 선택 처리
    $(".disease_tab").click(function () {
        $(this).toggleClass("selected"); // 선택/해제
        const selectedDiseases = $(".disease_tab.selected")
            .map(function () {
                return $(this).text().trim(); // 텍스트만 추출
            })
            .get()
            .join(","); // 콤마로 연결
        $("#disease_name").val(selectedDiseases); // 히든 필드 업데이트
    });

    // 진단 없음 클릭 시 다른 선택 제거
    $(".input_type_tab_clear").click(function () {
        $(".disease_tab").removeClass("selected"); // 다른 진단명 해제
        $("#disease_name").val("진단없음"); // 진단 없음 설정
    });

});

$('.input_tab').click(function(){
    $('.input_tab').removeClass('input_tab_on');
    $(this).addClass('input_tab_on');
});

$('.input_type_tab').click(function(){
    $(this).toggleClass('input_type_tab_on');
});

$('.input_type_tab_text').click(function(){
    $('.input_type_text').toggleClass('input_type_text_on');
});

$('.input_type_tab_clear').click(function(){
    $('.input_type_tab').removeClass('input_type_tab_on');
    $('.input_type_text').removeClass('input_type_text_on');
});

</script>
</x-layout>
