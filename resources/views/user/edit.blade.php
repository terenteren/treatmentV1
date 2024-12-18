<x-layout>

<style>
    /*고정*/
    .index_head, .index_menu_container{display: none;}
</style>

<span class="sub_top">
    <span class="back" onClick="location.href='/mypage'">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
    <span class="page_name">정보수정</span>
</span>

<form id="signupForm" action="{{ route('editMyPageStore') }}" method="POST">
    @csrf
    <span class="container_wrap sub_container_wrap">
        <span class="container sub_container sub_container1">
            <span class="input_wrap">

                <span class="input_container"> <!-- input_container start -->

                    {{-- 이메일 --}}
                    <span class="input_con">
                        <span class="input_name">먼저 회원가입이 필요해요</span>
                        <span class="input_box error_input_box">
                            <input type="email" id="email" value="{{ auth()->user()->email }}" readonly>
                        </span>
                    </span>

                    {{-- 이름 --}}
                    <span class="input_con">
                        <span class="input_name">이름</span>
                        <span class="input_box error_input_box">
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" placeholder="이름">
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
                                    <input type="hidden" name="sido" id="sido" value="{{ auth()->user()->profile->sido }}">
                                </span>
                            </span>
                            <span class="input_address">
                                <span class="input_select_wrap input_select_wrap2">
                                    <span class="input_select_box input_select_box2">구<img src="{{ asset('img/icon/down_arrow.svg') }}" alt="치료종류"></span>
                                    <span class="input_select_option_wrap input_select_option_wrap2"></span>
                                    <input type="hidden" name="sigugun" id="sigugun" value="{{ auth()->user()->profile->sigugun }}">
                                </span>
                            </span>
                        </span>
                    </span>

                </span> <!-- input_container end -->
            </span>
        </span>
    </span>

    <span class="input_btn_wrap blur">
        <button type="submit" class="input_btn" style="border: 0;">정보수정</button>
    </span>
</form>

<!-- 팝업 -->
<span class="popup" style="display: none;">
    <span class="popup_text">정보가 변경되었습니다.</span>
    <span class="popup_btn_wrap full_popup_btn_wrap">
        <span class="popup_btn popup_on close_popup_btn">확인</span>
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

    // 초기 시/도 값 동기화
    if (sidoInput.value) {
        sidoBox.textContent = sidoInput.value; // 숨겨진 필드 값이 있으면 표시
        const selectedCode = hangjungdong.sido.find(sido => sido.codeNm === sidoInput.value)?.sido;

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

        // 초기 시/군/구 값 동기화
        sigugunBox.textContent = sigugunInput.value || "구"; // 값이 없으면 기본값 표시
        if (sigugunInput.value) {
            sigugunBox.classList.remove("disabled"); // 값이 있으면 활성화
        }
    } else {
        sidoBox.textContent = "시/도"; // 기본값
    }

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
            sidoInput.value = selectedSido;

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

            // 선택된 값 표시
            sigugunBox.textContent = selectedSigugun;

            // 시/군/구 값 hidden input에 저장
            sigugunInput.value = selectedSigugun;

            // 하위 스팬 닫기
            sigugunOptionsWrap.classList.remove("open");
        }
    });

    // 초기화: .input_select_box2 비활성화
    if (!sigugunInput.value) {
        sigugunBox.classList.add("disabled");
    }

    // 초기화: 이전 시/도 값 초기화
    const form = document.getElementById("signupForm");
    const popupFail = document.querySelector(".send_popup_fail");
    const closePopupBtn = document.querySelector(".close_popup_btn");

    form.addEventListener("submit", (event) => {
        event.preventDefault(); // 기본 폼 제출 방지

        // 폼 필드 가져오기
        const name = form.querySelector("input[name='name']").value.trim();
        const sido = form.querySelector("input[name='sido']").value.trim();
        const sigugun = form.querySelector("input[name='sigugun']").value.trim();

        // 필드 검증
        if (!name || !sido || !sigugun) {
            alert("모든 필드를 입력해주세요."); // 사용자에게 알림
            return;
        }

        // 폼 제출
        form.submit();
    });

    // 플래시 메시지가 존재하면 팝업 표시
    @if(session('popup'))
        document.querySelector('.popup').style.display = 'block';
    @endif

    // 팝업 닫기 버튼 클릭 이벤트
    document.querySelector('.close_popup_btn').addEventListener('click', () => {
        document.querySelector('.popup').style.display = 'none';
    });

});


$(document).ready(function() {
    $('.check_input_btn').click(function(){
        $('.check_popup1').show();
    });
    $('.close_popup_btn').click(function(){
        $('.popup').hide();
    });

});
</script>
</x-layout>
