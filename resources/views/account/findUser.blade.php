<x-layout>

<style>
    /*고정*/
    .index_head, .index_menu_container{display: none;}
</style>

<span class="sub_top">
	<span class="back" id="goToBack"><img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기"></span>
	<span class="page_name">아이디/비밀번호찾기</span>
</span>

<span class="container_wrap sub_container_wrap">
	<span class="container sub_container sub_container1">
		<span class="input_tab_wrap find_input_tab_wrap">
			<span class="input_tab id_input_tab input_tab_on" id="findUser">아이디찾기</span>
			<span class="input_tab pw_input_tab" id="findPassword">비밀번호찾기</span>
		</span>

        <span class="input_con">
            <span class="input_name">핸드폰번호
                <span class="input_notice" style="display: none;">03:00</span>
            </span>
            <span class="input_phone">
                <span class="input_box">
                    <input type="text" id="phone" name="phone_number" placeholder="핸드폰번호를 입력해주세요">
                </span>
                <span class="input_phone_btn send_verification_code">인증</span>
            </span>
            <span class="input_box">
                <input type="text" name="verification_code" placeholder="인증번호 6자리">
            </span>
        </span>

	</span>
</span>

<span class="input_btn_wrap">
	<span class="input_btn check_input_btn find_id_input_btn">아이디찾기</span>
</span>

<span class="popup send_popup_try">
	<span class="popup_text">인증 버튼을 눌러 휴대폰 인증을 진행해주세요.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup send_popup">
	<span class="popup_text">인증번호가 발송되었습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup check_popup1">
	<span class="popup_text">입력하신 정보와 일치하는 아이디입니다.
	    <span id="check_popup1_email">"pm@witive.com"</span>
    </span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup check_popup2">
	<span class="popup_text">인증하신 핸드폰번호로
	가입하신 이력이 없습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup send_popup_phone_null">
	<span class="popup_text">핸드폰 번호를 입력해주세요.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<span class="popup send_popup_fail">
	<span class="popup_text">인증번호가 잘못 입력되었습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<span class="popup_btn popup_on close_popup_btn">확인</span>
	</span>
</span>

<script>
// 인증번호 발송 및 인증 처리
$(document).ready(function() {

    let countdownInterval; // 타이머 인터벌을 저장할 변수
    const countdownElement = $('.input_notice'); // 카운트다운 텍스트 요소

    // 카운트다운 시작 함수
    function startCountdown() {
        let timeLeft = 3 * 60; // 3분(초 단위)

        // 기존 인터벌이 있을 경우 제거 (재시작)
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        // 타이머 시작
        countdownElement.css('display', 'block'); // 카운트다운 텍스트 표시
        countdownInterval = setInterval(() => {
            // 남은 시간을 분:초 형식으로 표시
            const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            const seconds = (timeLeft % 60).toString().padStart(2, '0');
            countdownElement.text(`${minutes}:${seconds}`);

            if (timeLeft <= 0) {
                clearInterval(countdownInterval); // 타이머 중지
                countdownElement.text("00:00");

                // 세션의 인증번호를 삭제하는 요청
                $.post('/delete-verification-code', {
                    _token: '{{ csrf_token() }}'
                }).done(() => {
                    alert("인증 시간이 만료되었습니다. 다시 인증을 요청해주세요.");
                });
            }

            timeLeft -= 1;
        }, 1000);
    }

    // 인증번호 호출 버튼 클릭 시
    $('.send_verification_code').click(function() {
        const phoneNumber = $('input[name="phone_number"]').val();

        if (phoneNumber === '') {
            $('.send_popup_phone_null').show();
            return;
        }

        $.post('/send-verification-code', {
            phone_number: phoneNumber,
            _token: '{{ csrf_token() }}' // CSRF 토큰 추가
        })
        .done(response => {
            $('.send_popup').show(); // 인증번호 발송 팝업 표시
            startCountdown(); // 카운트다운 시작
        })
        .fail(error => {
            // console.log(error.responseJSON.message);
        });
    });

    // 아이디 찾기 버튼 클릭 시
    $('.find_id_input_btn').click(function() {
        const verificationCode = $('input[name="verification_code"]').val();
        const phoneNumber = $('input[name="phone_number"]').val();

        if (phoneNumber === '') {
            $('.send_popup_phone_null').show();
            return;
        }
        if (verificationCode === '') {
            $('.send_popup_try').show();
            return;
        }

        // 인증번호 검증 요청
        $.post('{{ route('verifyCodeAndReturnEmail') }}', {
            verification_code: verificationCode,
            phone_number: phoneNumber,
            _token: '{{ csrf_token() }}' // CSRF 토큰 추가
        })
            .done(response => {
                if (response.success) {
                    if (response.email === null) {
                        $('.check_popup2').show(); // 가입 이력 없음 팝업 표시
                        return;
                    } else {
                        $('#check_popup1_email').text(`"${response.email}"`);
                        $('.check_popup1').show();
                    }
                } else {
                    $('.send_popup_fail').show(); // 인증 실패 팝업 표시
                }
            })
            .fail(error => {
                $('.send_popup_fail').show(); // 인증 실패 팝업 표시
            });
    });

    // 팝업 닫기 버튼 클릭 시
    $('.close_popup_btn').click(function() {
        $(this).closest('.popup').hide(); // 해당 팝업 닫기
    });

    // findUser, findPassword 탭 클릭 시
    $('#findUser').click(function() {
        location.href = '{{ route('findUser') }}';
    });
    $('#findPassword').click(function() {
        location.href = '{{ route('findPassword') }}';
    });
    $('#goToBack').click(function() {
        location.href = '{{ route('login') }}';
    });

});
</script>
</x-layout>
