<x-layout>

@if ($errors->has('loginError'))
    <div class="error-message">
        {{ $errors->first('loginError') }}
    </div>
@endif

<span class="container_wrap login_container_wrap">
	<span class="container login_container">
		<span class="login_wrap">
            <form method="POST" id="loginForm" action="{{ route('loginStore') }}">
                @csrf
                <span class="login_logo_wrap">
                    <span class="login_logo"><img src="{{ asset('img/logo/logo.png') }}" alt="서울대병원 - 치료모니터링"></span>
                </span>
                <span class="login_input_wrap">
                    <span class="input_container">
                        <span class="input_con">
                            <span class="input_name">아이디</span>
                            <span class="input_box input_box1">
                                <input type="email" name="email" placeholder="아이디를 입력해주세요">
                            </span>
                        </span>
                        <span class="input_con">
                            <span class="input_name">비밀번호</span>
                            <span class="input_box input_box1">
                                <input type="password" name="password" placeholder="비밀번호를 입력해주세요">
                            </span>
                        </span>
                    </span>
                </span>
                <span class="check_wrap">
                    <span class="check_left">
                        <span class="checkbox_wrap">
                            <input type="checkbox" id="check" name="remember">
                            <label for="check">
                                <span class="check_img_wrap">
                                    <span class="check_img check_img_off"><img src="{{ asset('img/icon/check_off.svg') }}" alt="체크 꺼짐"></span>
                                    <span class="check_img check_img_on"><img src="{{ asset('img/icon/check_on.svg') }}" alt="체크 켜짐"></span>
                                </span>
                                <span class="check_name">자동로그인</span>
                            </label>
                        </span>
                    </span>
                    <span class="check_right">
                        <a href="{{ route('findUser') }}" class="find_account">아이디/비밀번호찾기</a>
                    </span>
                </span>
                <span class="login_btn_wrap">
                    <button type="submit" class="input_btn login_btn" style="border: 0;"><span>로그인</span></button>
                    <span class="input_btn sns_btn"><img src="./img/icon/sns/kakao.svg" alt="Kakao로 로그인"><span>Kakao</span>로 로그인</span>
                    <span class="input_btn sns_btn"><img src="./img/icon/sns/naver.svg" alt="Naver로 로그인"><span>Naver</span>로 로그인</span>
                    <span class="input_btn sns_btn"><img src="./img/icon/sns/google.svg" alt="Google로 로그인"><span>Google</span>로 로그인</span>
                    <span class="input_btn sns_btn"><img src="./img/icon/sns/apple.svg" alt="Apple로 로그인"><span>Apple</span>로 로그인</span>
                </span>
            </form>
			<span class="social_btn_wrap">
				<a href="{{ route('signup1') }}" class="input_btn signup_btn" >회원가입</a>
			</span>
		</span>
	</span>
</span>

</x-layout>
