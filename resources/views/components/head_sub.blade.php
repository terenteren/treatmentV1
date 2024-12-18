
<span class="index_head">
	<span class="index_head_left">
		<span class="index_head_user_info">
			<span class="index_head_user_name">{{ $data['selectedChildProfile']->kid_name }}</span>
			<span class="index_head_user_age">생활연령 {{ max(floor(\Carbon\Carbon::parse($data['selectedChildProfile']->birth)->diffInMonths(now(), false)), 0) }}개월</span>
		</span>
	</span>

	<span class="index_head_right">
		<a href="{{ route('mypage') }}" class="index_head_user_img">
            @if($data['selectedChildProfile'] && $data['selectedChildProfile']->profile_image)
                <img src="{{ asset('storage/' . $data['selectedChildProfile']->profile_image) }}" alt="대표 자녀">
            @else
                <img src="{{ asset('img/default_user.png') }}" alt="기본 이미지">
            @endif
        </a>
	</span>
</span>

<span class="index_menu_container">
	<a href="./index.php" class="index_menu_con index_menu_con1 {{ $data['page_step'] == 'index_menu_con1_on' ? 'index_menu_con_on' : '' }}">
		<span class="index_menu_img">
			<img src="{{ asset('img/icon/index_menu1_off.svg') }}" alt="홈" class="index_menu_img_off">
			<img src="{{ asset('img/icon/index_menu1_on.svg') }}" alt="홈" class="index_menu_img_on">
		</span>
		<span class="index_menu_name">홈</span>
	</a>
	<a href="./memo_search.php" class="index_menu_con index_menu_con2 {{ $data['page_step'] == 'index_menu_con2_on' ? 'index_menu_con_on' : '' }}">
		<span class="index_menu_img">
			<img src="{{ asset('img/icon/index_menu2_off.svg') }}" alt="메모" class="index_menu_img_off">
			<img src="{{ asset('img/icon/index_menu2_on.svg') }}" alt="메모" class="index_menu_img_on">
		</span>
		<span class="index_menu_name">메모</span>
	</a>
	<a href="./develop.php" class="index_menu_con index_menu_con3 {{ $data['page_step'] == 'index_menu_con3_on' ? 'index_menu_con_on' : '' }}">
		<span class="index_menu_img">
			<img src="{{ asset('img/icon/index_menu3_off.svg') }}" alt="발달평가" class="index_menu_img_off">
			<img src="{{ asset('img/icon/index_menu3_on.svg') }}" alt="발달평가" class="index_menu_img_on">
		</span>
		<span class="index_menu_name">발달평가</span>
	</a>
	<a href="./action.php" class="index_menu_con index_menu_con3 {{ $data['page_step'] == 'index_menu_con4_on' ? 'index_menu_con_on' : '' }}">
		<span class="index_menu_img">
			<img src="{{ asset('img/icon/index_menu4_off.svg') }}" alt="도전행동" class="index_menu_img_off">
			<img src="{{ asset('img/icon/index_menu4_on.svg') }}" alt="도전행동" class="index_menu_img_on">
		</span>
		<span class="index_menu_name">도전행동</span>
	</a>
	<a href="./history.php" class="index_menu_con index_menu_con4 {{ $data['page_step'] == 'index_menu_con5_on' ? 'index_menu_con_on' : '' }}">
		<span class="index_menu_img">
			<img src="{{ asset('img/icon/index_menu5_off.svg') }}" alt="히스토리" class="index_menu_img_off">
			<img src="{{ asset('img/icon/index_menu5_on.svg') }}" alt="히스토리" class="index_menu_img_on">
		</span>
		<span class="index_menu_name">히스토리</span>
	</a>
</span>




