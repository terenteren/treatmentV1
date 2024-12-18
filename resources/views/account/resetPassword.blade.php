<x-layout>

<style>
    .index_head, .index_menu_container{display: none;}
    .non_border{border: none;}
</style>

<span class="sub_top">
	<span class="back" id="goToBack"><img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기"></span>
	<span class="page_name">아이디/비밀번호찾기</span>
</span>

<span class="container_wrap sub_container_wrap">
	<span class="container sub_container sub_container1">

		<form method="POST" action="{{ route('account.handleResetPassword') }}">
            @csrf
            <span class="input_wrap new_pw_input_wrap">
                <span class="input_container">
                    <span class="input_con">
                        <span class="input_name">새 비밀번호</span>
                        <span class="input_box">
                            <input type="password" name="password" placeholder="새 비밀번호를 6자리 이상 입력해주세요">
                        </span>
                    </span>
                    <span class="input_con">
                        <span class="input_name">새 비밀번호 확인</span>
                        <span class="input_box">
                            <input type="password" name="password_confirmation" placeholder="새 비밀번호를 다시 한번 입력해주세요">
                        </span>
                    </span>
                </span>
            </span>

            <span class="input_btn_wrap">
                <button type="submit" class="input_btn check_input_btn new_pw_input_btn non_border">
                    비밀번호 재설정
                </button>
            </span>
        </form>

	</span>
</span>

<span class="popup check_popup4">
	<span class="popup_text">비밀번호 변경이 완료되었습니다.
	변경하신 비밀번호로 로그인해주세요.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<a href="{{ route('login') }}" class="popup_btn popup_on">확인</a>
	</span>
</span>

<span class="popup send_popup mismatch_popup">
	<span class="popup_text">비밀번호 확인과 비밀번호가 일치하지 않습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup send_popup success_popup" style="display: none;">
    <span class="popup_text">비밀번호가 성공적으로 변경되었습니다.</span>
    <span class="popup_btn_wrap full_popup_btn_wrap">
        <span class="popup_btn popup_on close_popup_btn">확인</span>
    </span>
</span>

<script>
$(document).ready(function() {
    // 팝업 닫기 버튼 클릭 시
    $('.close_popup_btn').click(function() {
        $(this).closest('.popup').hide(); // 해당 팝업 닫기
    });

    // 뒤로가기 버튼 처리
    $('#goToBack').click(function() {
        location.href = '{{ route('login') }}';
    });

    // 비밀번호 확인 처리
    $('.new_pw_input_btn').click(function() {
        var password = $('input[name="password"]').val(); // 새 비밀번호 입력값
        var confirmPassword = $('input[name="password_confirmation"]').val(); // 비밀번호 확인 입력값

        if (password !== confirmPassword) {
            // 비밀번호 불일치 시 팝업 표시
            $('.mismatch_popup').show();
            return false;
        } else {
            // 폼 제출
            $('form').submit();
        }
    });

    // 성공 여부 확인
    @if(isset($success) && $success)
        // 성공 팝업 표시
        $('.success_popup').show();

        // 확인 버튼 클릭 시 로그인 페이지로 이동
        $('.close_popup_btn').click(function () {
            window.location.href = '{{ route('login') }}';
        });
    @endif
});

</script>
</x-layout>
