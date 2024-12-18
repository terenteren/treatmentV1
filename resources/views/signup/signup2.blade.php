<x-layout>
    <style>
        .index_head, .index_menu_container { display: none; }
    </style>

    <span class="sub_top">
        <span class="back" onClick="history.go(-1)">
            <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
        </span>
        <span class="page_name">회원가입</span>
    </span>

    <form method="POST" action="{{ route('processSignup2') }}">
        @csrf
        <span class="container_wrap sub_container_wrap">
            <span class="container sub_container sub_container1">
                <span class="input_wrap">
                    <span class="input_container">
                        <span class="input_con">
                            <span class="input_name">비밀번호 입력</span>
                            <span class="input_box"><input type="password" name="password" placeholder="6자리 이상 입력해주세요"></span>
                        </span>
                        <span class="input_con">
                            <span class="input_name">비밀번호 확인</span>
                            <span class="input_box"><input type="password" name="password_confirm" placeholder="다시 한번 입력해주세요"></span>
                        </span>
                    </span>
                </span>
            </span>
        </span>

        <span class="input_btn_wrap blur">
            <button type="submit" class="input_btn" style="border: 0;">계속하기</button>
        </span>
    </form>

</x-layout>
