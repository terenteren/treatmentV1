<x-layout>

<style>
    /*고정*/
    .index_head, .index_menu_container{display: none;}
</style>
    <span class="sub_top">
	<span class="back" onClick="location.href='/mypage'">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
	<span class="page_name">마이페이지</span>
</span>

<form id="signupForm" action="{{ route('changeSelectedChildStore') }}" method="POST">
    @csrf
    <input type="hidden" name="selected_child_id" id="selected_child_id" value="{{ auth()->user()->profile->selected_child_id }}">
    <span class="container_wrap">
        <span class="container">
            <span class="mypage_wrap">
                @foreach(auth()->user()->childProfiles as $child)
                    <span class="mypage_info_wrap {{ $child->id != auth()->user()->profile->selected_child_id ? 'mypage_info_wrap_off' : '' }}"
                          data-child-id="{{ $child->id }}">
                    <span class="mypage_profile_img">
                        <img src="{{ asset('storage/'. $child->profile_image) }}" alt="프로필이미지">
                    </span>
                    <span class="mypage_info_box">
                        <span class="mypage_name">{{ $child->kid_name }}({{ $child->gender == '남자' ? '남자' : '여자' }})</span>
                        <span class="mypage_age">
                            {{ \Carbon\Carbon::parse($child->birth)->format('Y-m-d') }}
                            ({{ max(floor(\Carbon\Carbon::parse($child->birth)->diffInMonths(now(), false)), 0) }}개월)
                        </span>
                    </span>
                </span>
                @endforeach
            </span>
        </span>
    </span>

    <span class="input_btn_wrap blur">
        <button type="submit" class="input_btn" style="border: 0;">자녀 변경하기</button>
    </span>
</form>


<span class="popup check_popup1" style="display: none;">
	<span class="popup_text">대표 자녀가 변경되었습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
        <a href="#" class="popup_btn popup_on" id="submit-to-signup2">확인</a>
	</span>
</span>

<script>
$(document).ready(function() {
    // 마이페이지 자녀 정보 클릭 이벤트
    $('.mypage_info_wrap').click(function() {
        // 모든 항목에 비활성화 클래스를 추가
        $('.mypage_info_wrap').addClass('mypage_info_wrap_off');

        // 클릭한 항목에서 비활성화 클래스를 제거
        $(this).removeClass('mypage_info_wrap_off');

        // 선택된 자녀의 ID를 가져와 hidden 필드에 설정
        const selectedChildId = $(this).data('child-id');
        $('#selected_child_id').val(selectedChildId);
    });

    // 팝업 표시
    @if(session('popup'))
        $('.check_popup1').css('display', 'block');
    @endif

    // 팝업 닫기
    $('#submit-to-signup2').click(function() {
        $('.check_popup1').css('display', 'none');
    });

});
</script>
</x-layout>
