<x-layout>

@include('components.head_sub', ['data' => $data])

<style>
    /*고정*/
    .index_head{display: none;}
</style>

<span class="sub_top">
	<span class="back" onClick="location.href='/';"><img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기"></span>
	<span class="page_name">마이페이지</span>
	<a href="{{ route('showSelectedChild') }}" class="change" style="float: left;">
        <img src="{{ asset('img/icon/change.svg') }}" alt="수정">
    </a>
	<a href="{{ route('editMyPage') }}" class="retouch" style="float: right;">
        <img src="{{ asset('img/icon/setting.svg') }}" alt="수정">
    </a>
</span>
<span class="container_wrap">
	<span class="container">
		<span class="mypage_wrap">
            @foreach(auth()->user()->childProfiles as $child)
            <span class="mypage_info_wrap">
                <a href="{{ route('updateChild', ['id' => $child->id]) }}">
                    <span class="mypage_profile_img">
                        <img src="{{ asset('storage/'. $child->profile_image) }}" alt="프로필이미지">
                        <span class="mypage_profile_img_btn">
                            <img src="{{ asset('img/icon/camera_white.svg') }}" alt="프로필이미지 등록">
                        </span>
                    </span>
                </a>
                <span class="mypage_info_box">
                    <span class="mypage_name">{{ $child->kid_name }}({{ $child->gender == '남자' ? '남자' : '여자' }})</span>
                    <span class="mypage_age">
                        {{ \Carbon\Carbon::parse($child->birth)->format('Y-m-d') }}
                        ({{ max(floor(\Carbon\Carbon::parse($child->birth)->diffInMonths(now(), false)), 0) }}개월)
                    </span>
                </span>
                @if($loop->last)
                    <a href="{{ route('addChild') }}" class="mypage_add_btn_wrap">
                        <span class="mypage_add_btn">
                            <img src="{{ asset('img/icon/add_white.svg') }}" alt="자녀추가하기">
                        </span>
                    </a>
                @endif
            </span>
            @endforeach
			<span class="mypage_menu_wrap">
				<a href="" class="mypage_menu">공지사항</a>
				<a href="./mypage_trash_list.php" class="mypage_menu">삭제내역</a>
				<a href="" class="mypage_menu">푸쉬알림</a>
				<a href="" class="mypage_menu">고객센터</a>
			</span>
		</span>
	</span>
</span>


<script>
$(document).ready(function() {

});
</script>
</x-layout>
