<x-layout>

<style>
    /*고정*/
    .index_head, .index_menu_container{display: none;}
    .container_wrap{padding-bottom: 310px;}
</style>

<span class="sub_top">
	<span class="back" onClick="history.go(-1)">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
	<span class="page_name">회원가입</span>
</span>

<span class="container_wrap sub_container_wrap">
	<span class="container sub_container sub_container1">
		<span class="input_wrap">
			<span class="input_title">반가워요! 우리 함께 시작할까요?</span>
			<span class="input_container"> <!-- input_container start -->

                {{-- 이메일 --}}
                <span class="input_con">
                    <span class="input_name">먼저 회원가입이 필요해요</span>
                    <span class="input_box error_input_box">
                        <input type="email" name="email" id="email" placeholder="이메일(아이디) 주소를 입력해주세요">
                    </span>
                    <span class="input_error check_error_email" style="display: none">*사용중인 이메일입니다. 다른 이메일주소를 입력해주세요.</span>
                </span>

                {{-- 이름 --}}
                <span class="input_con">
                    <span class="input_name">이름</span>
                    <span class="input_box error_input_box">
                        <input type="text" name="name" id="name" placeholder="이름">
                    </span>
                </span>

                {{-- 핸드폰번호 --}}
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

                {{-- 거주지 --}}
                <span class="input_con">
                    <span class="input_name">거주지</span>
                    <span class="input_address_wrap">
                        <span class="input_address">
                            <span class="input_select_wrap input_select_wrap1">
                                <span class="input_select_box input_select_box1">시/도
                                    <img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류">
                                </span>
                                <span class="input_select_option_wrap input_select_option_wrap1"></span>
                                <input type="hidden" name="sido" id="sido">
                            </span>
                        </span>
                        <span class="input_address">
                            <span class="input_select_wrap input_select_wrap2">
                                <span class="input_select_box input_select_box2">구<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                                <span class="input_select_option_wrap input_select_option_wrap2"></span>
                                <input type="hidden" name="sigugun" id="sigugun">
                            </span>
                        </span>
                    </span>
                </span>

			</span> <!-- input_container end -->
		</span>
	</span>
</span>

<span class="input_btn_wrap blur">
	<span class="input_btn check_input_btn">인증하기</span>
</span>

<span class="popup send_popup_phone_null">
	<span class="popup_text">핸드폰 번호를 입력해주세요.</span>
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

<span class="popup send_popup_try">
	<span class="popup_text">인증 버튼을 눌러 휴대폰 인증을 진행해주세요.</span>
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

<span class="popup check_popup1">
	<span class="popup_text">인증이 완료되었습니다.</span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
        <a href="#" class="popup_btn popup_on" id="submit-to-signup2">확인</a>
	</span>
</span>

<span class="popup already_registered_popup" style="display: none;">
	<span class="popup_text">이미 가입된 유저입니다.</span>
    <span class="popup_text">가입된 이메일: <span class="registered_email"></span></span>
	<span class="popup_btn_wrap full_popup_btn_wrap">
		<a href="/login" class="popup_btn popup_on">확인</a>
	</span>
</span>

<style>
.input_select_option_wrap {
    display: none;
    position: absolute;
    z-index: 10;
    background: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
}

.input_select_option_wrap.open {
    display: block;
}

.input_select_box.disabled {
    color: #ccc;
    pointer-events: none;
}
</style>
<script src="{{ asset('/assets/js/hangjungdong.js') }}"></script>
<script>
// 시/도, 시/군/구 선택 스크립트
document.addEventListener("DOMContentLoaded", () => {
    const sidoOptionsWrap = document.querySelector(".input_select_option_wrap1");
    const sigugunOptionsWrap = document.querySelector(".input_select_option_wrap2");
    const sidoBox = document.querySelector(".input_select_box1");
    const sigugunBox = document.querySelector(".input_select_box2");
    const sidoInput = document.querySelector("#sido");
    const sigugunInput = document.querySelector("#sigugun");

    let previousSido = ""; // 이전 선택된 시/도 값

    // 초기 시/도 옵션 로드
    hangjungdong.sido.forEach(sido => {
        const span = document.createElement("span");
        span.className = "input_select_option input_select_option1";
        span.dataset.location = "sido";
        span.textContent = sido.codeNm;
        span.dataset.code = sido.sido; // 시/도 코드 저장
        sidoOptionsWrap.appendChild(span);
    });

    // 시/도 박스 클릭 이벤트
    sidoBox.addEventListener("click", () => {
        sidoOptionsWrap.classList.toggle("open");
        sigugunOptionsWrap.classList.remove("open"); // 다른 옵션창 닫기
    });

    // 시/도 옵션 클릭 이벤트
    sidoOptionsWrap.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("input_select_option1")) {
            const selectedSido = target.textContent;
            const selectedCode = target.dataset.code;

            if (previousSido !== selectedSido) {
                // 시/도 값 변경 시 시/군/구 초기화
                sigugunBox.textContent = "구"; // 초기 상태로 변경
                sigugunBox.classList.add("disabled");
                sigugunOptionsWrap.innerHTML = ""; // 기존 옵션 제거
                sigugunInput.value = ""; // 숨겨진 입력 초기화
            }

            // 선택된 시/도 값 표시
            sidoBox.textContent = selectedSido;

            // 시/도 값 hidden input에 저장
            sidoInput.value = sidoBox.textContent;
            // sidoInput.value = selectedCode;

            // 하위 스팬 닫기
            sidoOptionsWrap.classList.remove("open");

            // 시/군/구 옵션 로드
            hangjungdong.sigugun
                .filter(sigugun => sigugun.sido === selectedCode)
                .forEach(sigugun => {
                    const span = document.createElement("span");
                    span.className = "input_select_option input_select_option2";
                    span.dataset.sigugun = sigugun.sigugun;
                    span.textContent = sigugun.codeNm;
                    sigugunOptionsWrap.appendChild(span);
                });

            // 시/군/구 박스 활성화
            sigugunBox.classList.remove("disabled");

            // 이전 시/도 값 업데이트
            previousSido = selectedSido;
        }
    });

    // 시/군/구 박스 클릭 이벤트
    sigugunBox.addEventListener("click", () => {
        if (!sigugunBox.classList.contains("disabled")) {
            sigugunOptionsWrap.classList.toggle("open");
            sidoOptionsWrap.classList.remove("open"); // 다른 옵션창 닫기
        }
    });

    // 시/군/구 옵션 클릭 이벤트
    sigugunOptionsWrap.addEventListener("click", (event) => {
        const target = event.target;
        if (target.classList.contains("input_select_option2")) {
            const selectedSigugun = target.textContent;
            const selectedSigugunCode = target.dataset.sigugun; // code

            // 선택된 값 표시
            sigugunBox.textContent = selectedSigugun;

            // 시/군/구 값 hidden input에 저장
            sigugunInput.value = selectedSigugun;
            // sigugunInput.value = selectedSigugunCode;

            // 하위 스팬 닫기
            sigugunOptionsWrap.classList.remove("open");
        }
    });

    // 초기화: .input_select_box2 비활성화
    sigugunBox.classList.add("disabled");
});


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
            // alert(response.message);
        })
        .fail(error => {
            // alert(error.responseJSON.message);
        });
    });

    // 인증하기 버튼 클릭 시
    $('.check_input_btn').click(function() {
        const verificationCode = $('input[name="verification_code"]').val();
        const phoneNumber = $('input[name="phone_number"]').val();

        if (verificationCode === '') {
            $('.send_popup_try').show();
            return;
        }

        // 인증번호 검증 요청
        $.post('/verify-code', {
            verification_code: verificationCode,
            phone_number: phoneNumber,
            _token: '{{ csrf_token() }}' // CSRF 토큰 추가
        })
        .done(response => {
            if (response.success) {
                // 인증 성공 후 핸드폰 번호 중복 확인
                $.post('/check-phone-number', {
                    phone_number: phoneNumber,
                    _token: '{{ csrf_token() }}' // CSRF 토큰 추가
                })
                .done(phoneCheckResponse => {
                    if (phoneCheckResponse.exists) {
                        // 이미 가입된 유저
                        $('.already_registered_popup .registered_email').text(phoneCheckResponse.email); // 가입된 이메일 표시
                        $('.already_registered_popup').show();
                    } else {
                        // 신규 사용자
                        $('.check_popup1').show(); // 인증 성공 팝업 표시
                    }
                })
                .fail(error => {
                    error.responseJSON && alert(error.responseJSON.message);
                    alert('번호 확인 중 문제가 발생했습니다.');
                });
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

    // 이메일 입력 필드에서 커서가 벗어날 때
    $('input[name="email"]').on('blur', function() {
        const email = $(this).val();

        // 이메일이 입력된 경우에만 중복 체크 실행
        if (email) {
            $.ajax({
                url: '/check-email', // 이메일 중복 확인을 위한 라우트
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}' // CSRF 토큰 추가
                },
                success: function(response) {
                    if (response.exists) {
                        $('.check_error_email').show(); // 이메일 중복 시 오류 메시지 표시
                    } else {
                        $('.check_error_email').hide(); // 이메일 중복이 아닐 경우 오류 메시지 숨김
                    }
                },
                error: function() {
                    console.log("이메일 중복 확인 중 오류가 발생했습니다.");
                }
            });
        } else {
            $('.check_error_email').hide(); // 이메일 입력이 없는 경우 오류 메시지 숨김
        }
    });

    // 회원가입 완료 시
    $('#submit-to-signup2').click(function (e) {
        e.preventDefault(); // 기본 동작 중단

        // 데이터 수집
        const email = $('input[name="email"]').val();
        const name = $('input[name="name"]').val();
        const phoneNumber = $('input[name="phone_number"]').val();
        const sido = $('input[name="sido"]').val();
        const sigugun = $('input[name="sigugun"]').val();

        // 동적으로 폼 생성
        const form = $('<form>', {
            action: '{{ route('processSignup1') }}',
            method: 'POST'
        });

        // CSRF 토큰 추가
        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}' // CSRF 토큰
        }));

        // 수집한 데이터 추가
        form.append($('<input>', { type: 'hidden', name: 'email', value: email }));
        form.append($('<input>', { type: 'hidden', name: 'name', value: name }));
        form.append($('<input>', { type: 'hidden', name: 'phone_number', value: phoneNumber }));
        form.append($('<input>', { type: 'hidden', name: 'sido', value: sido }));
        form.append($('<input>', { type: 'hidden', name: 'sigugun', value: sigugun }));

        // 폼을 body에 추가하고 제출
        $('body').append(form);
        form.submit();
    });

});
</script>

</x-layout>
