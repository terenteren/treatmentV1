<x-layout>

@include('components.head_sub', ['data' => $data])

<span class="index_container_wrap">
	<span class="index_info_wrap">
		<span class="index_info_container">
			<span class="index_info_con">
				<span class="index_info_inner">
					<span class="index_info_img"><img src="{{ asset('img/icon/therapy_new.svg') }}" alt="받고있는 치료"></span>
					<span class="index_info_textbox">
						<span class="index_info_name">받고있는 치료</span>
						<span class="index_info_num">{{ $data['disease_name_cnt'] }}</span>
					</span>
				</span>
			</span>
			<span class="index_info_con">
				<span class="index_info_inner">
					<span class="index_info_img"><img src="{{ asset('img/icon/drug_new.svg') }}" alt="먹고있는 약"></span>
					<span class="index_info_textbox">
						<span class="index_info_name">먹고있는 약</span>
						<span class="index_info_num">{{ $data['drug_record_cnt'] }}</span>
					</span>
				</span>
			</span>
		</span>
	</span>
	<span class="index_item_wrap index_item_wrap1">
		<span class="index_item_title_wrap">
			<span class="index_item_title"><span>{{ $data['selectedChildProfile']->kid_name }}</span>가 받고 있는 치료에요</span>
			<span class="index_item_title_btn_wrap">
				<a href="./medical_write.php" class="index_item_title_btn">치료 추가 +</a>
			</span>
		</span>
		<span class="index_item_container">
{{--			<span class="index_item_con index_item_con_on">--}}
{{--				<a href="./medical.php" class="index_item_icon_wrap">--}}
{{--					<span class="index_item_icon">--}}
{{--						<img src="{{ asset('img/icon/therapy_white.svg') }}" alt="치료" class="index_item_icon_on">--}}
{{--						<img src="{{ asset('img/icon/therapy_new.svg') }}" alt="치료" class="index_item_icon_off">--}}
{{--					</span>--}}
{{--					<span class="index_item_btn">--}}
{{--						<img src="{{ asset('img/icon/right_arrow_white.svg') }}" alt="자세히보기" class="index_item_btn_on">--}}
{{--						<img src="{{ asset('img/icon/right_arrow_off.svg') }}" alt="자세히보기" class="index_item_btn_off">--}}
{{--					</span>--}}
{{--				</a>--}}
{{--				<span class="index_item_name">ABA치료</span>--}}
{{--				<span class="index_item_location">(치료실명)</span>--}}
{{--			</span>--}}

            @foreach(auth()->user()->childTreatments as $Treatment)
			<span class="index_item_con">
				<a href="./medical.php" class="index_item_icon_wrap">
					<span class="index_item_icon">
						<img src="{{ asset('img/icon/therapy_white.svg') }}" alt="치료" class="index_item_icon_on">
						<img src="{{ asset('img/icon/therapy_new.svg') }}" alt="치료" class="index_item_icon_off">
					</span>
					<span class="index_item_btn">
						<img src="{{ asset('img/icon/right_arrow_white.svg') }}" alt="자세히보기" class="index_item_btn_on">
						<img src="{{ asset('img/icon/right_arrow_off.svg') }}" alt="자세히보기" class="index_item_btn_off">
					</span>
				</a>
				<span class="index_item_name">{{ $Treatment->kind }}</span>
				<span class="index_item_location">(치료실명)</span>
			</span>
            @endforeach

		</span>
	</span>

	<span class="index_item_wrap index_item_wrap2">
		<span class="index_item_title_wrap">
			<span class="index_item_title"><span>{{ $data['selectedChildProfile']->kid_name }}</span>가 먹고 있는 약이에요</span>
			<span class="index_item_title_btn_wrap">
				<a href="./drug_write.php" class="index_item_title_btn index_item_title_btn1">약 추가 +</a>
				<a href="./drug_error.php" class="index_item_title_btn index_item_title_btn2">부작용 +</a>
			</span>
		</span>
		<span class="index_item_container">
{{--			<span class="index_item_con index_item_con_on">--}}
{{--				<a href="./drug.php" class="index_item_icon_wrap">--}}
{{--					<span class="index_item_icon">--}}
{{--						<img src="{{ asset('img/icon/drug_white.svg') }}" alt="치료" class="index_item_icon_on">--}}
{{--						<img src="{{ asset('img/icon/drug_new.svg') }}" alt="치료" class="index_item_icon_off">--}}
{{--					</span>--}}
{{--					<span class="index_item_btn">--}}
{{--                        <img src="{{ asset('img/icon/right_arrow_white.svg') }}" alt="자세히보기" class="index_item_btn_on">--}}
{{--						<img src="{{ asset('img/icon/right_arrow_off.svg') }}" alt="자세히보기" class="index_item_btn_off">--}}
{{--					</span>--}}
{{--				</a>--}}
{{--				<span class="index_item_name">ABA치료</span>--}}
{{--				<span class="index_item_location">(치료실명)</span>--}}
{{--			</span>--}}

            @foreach($data['drugRecords'] as $record)
                <span class="index_item_con">
                    <a href="{{ route('showDrugRecord', ['id' => $record->id]) }}" class="index_item_icon_wrap">
                        <span class="index_item_icon">
                            <img src="{{ asset('img/icon/drug_white.svg') }}" alt="약물" class="index_item_icon_on">
                            <img src="{{ asset('img/icon/drug_new.svg') }}" alt="약물" class="index_item_icon_off">
                        </span>
                        <span class="index_item_btn">
                            <img src="{{ asset('img/icon/right_arrow_white.svg') }}" alt="자세히보기" class="index_item_btn_on">
                            <img src="{{ asset('img/icon/right_arrow_off.svg') }}" alt="자세히보기" class="index_item_btn_off">
                        </span>
                    </a>
                    <span class="index_item_name">{{ $record->drug->item_name ?? '알 수 없는 약물' }}</span>
                    <span class="index_item_location">({{ $record->drug->entp_name ?? '출처 없음' }})</span>
                </span>
            @endforeach


		</span>
	</span>
	<span class="index_item_wrap index_item_wrap3">
		<span class="index_item_title_wrap">
			<span class="index_item_title">발달기능 평가결과</span>
		</span>
		<span  class="index_graph_wrap">
			<span class="index_graph_top">
				<span class="index_graph_arrow index_graph_prev"><img src="{{ asset('img/icon/left_arrow_new.svg') }}" alt="PREV"></span>
				<span class="index_graph_date">2024년 10월 21일</span>
				<span class="index_graph_arrow index_graph_next"><img src="{{ asset('img/icon/right_arrow_new.svg') }}" alt="NEXT"></span>
			</span>
			<span class="index_graph_container">
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar1" style="height: 70%"></span>
						</span>
					</span>
					<span class="index_graph_name">수용
					언어</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar2" style="height: 45%"></span>
						</span>
					</span>
					<span class="index_graph_name">표현
					언어</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar1" style="height: 60%"></span>
						</span>
					</span>
					<span class="index_graph_name">대근육</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar1" style="height: 75%"></span>
						</span>
					</span>
					<span class="index_graph_name">소근육</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar" style="height: 0%"></span>
						</span>
					</span>
					<span class="index_graph_name">사회
					기술</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar2" style="height: 35%"></span>
						</span>
					</span>
					<span class="index_graph_name">인지
					모방</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar2" style="height: 45%"></span>
						</span>
					</span>
					<span class="index_graph_name">놀이</span>
				</span>
				<span class="index_graph_con">
					<span class="index_graph_box">
						<span class="index_graph_inner">
							<span class="index_graph_bar index_graph_bar2" style="height: 47%"></span>
						</span>
					</span>
					<span class="index_graph_name">자조</span>
				</span>
			</span>
		</span>
	</span>
	<span class="index_item_wrap index_item_wrap3">
		<span class="index_item_title_wrap">
			<span class="index_item_title">도전행동 평가결과</span>
			<span class="index_graph_info_wrap">
				<span class="index_graph_info">
					<span class="index_graph_info_bar index_graph_info_bar1"></span>
					<span class="index_graph_info_name">빈도</span>
				</span>
				<span class="index_graph_info">
					<span class="index_graph_info_bar index_graph_info_bar2"></span>
					<span class="index_graph_info_name">심각도</span>
				</span>
			</span>
		</span>
		<span class="index_action_container">
			<span class="index_action_con">
				<span class="index_action_name">상동행동</span>
				<span class="index_action_score_wrap">
					<span class="index_action_score index_action_score1">18/24</span>
					<span class="index_action_score index_action_score2">5/24</span>
				</span>
				<span class="index_action_date">24.8.25 기준</span>
			</span>
			<span class="index_action_con">
				<span class="index_action_name">파괴행동</span>
				<span class="index_action_score_wrap">
					<span class="index_action_score index_action_score1">18/24</span>
					<span class="index_action_score index_action_score2">5/24</span>
				</span>
				<span class="index_action_date">24.8.25 기준</span>
			</span>
			<span class="index_action_con">
				<span class="index_action_name">자해행동</span>
				<span class="index_action_score_wrap">
					<span class="index_action_score index_action_score1">18/24</span>
					<span class="index_action_score index_action_score2">5/24</span>
				</span>
				<span class="index_action_date">24.8.25 기준</span>
			</span>
		</span>
		<span  class="index_graph_wrap">
			<span class="index_graph_top">
				<span class="index_graph_arrow index_graph_prev"><img src="{{ asset('img/icon/left_arrow_new.svg') }}" alt="PREV"></span>
				<span class="index_graph_date">2024년 10월 21일</span>
				<span class="index_graph_arrow index_graph_next"><img src="{{ asset('img/icon/right_arrow_new.svg') }}" alt="NEXT"></span>
			</span>
			<span class="index_action_graph_container">
				<span class="index_action_graph_con">
					<span class="index_action_graph_box">
						<span class="index_action_graph_inner">
							<span class="index_action_graph_bar index_action_graph_bar1"><span style="height: 65%;"></span></span>
							<span class="index_action_graph_bar index_action_graph_bar2"><span style="height: 15%;"></span></span>
						</span>
					</span>
					<span class="index_action_graph_name">상동행동</span>
				</span>
				<span class="index_action_graph_con">
					<span class="index_action_graph_box">
						<span class="index_action_graph_inner">
							<span class="index_action_graph_bar index_action_graph_bar1"><span style="height: 65%;"></span></span>
							<span class="index_action_graph_bar index_action_graph_bar2"><span style="height: 25%;"></span></span>
						</span>
					</span>
					<span class="index_action_graph_name">파괴행동</span>
				</span>
				<span class="index_action_graph_con">
					<span class="index_action_graph_box">
						<span class="index_action_graph_inner">
							<span class="index_action_graph_bar index_action_graph_bar1"><span style="height: 65%;"></span></span>
							<span class="index_action_graph_bar index_action_graph_bar2"><span style="height: 25%;"></span></span>
						</span>
					</span>
					<span class="index_action_graph_name">자해행동</span>
				</span>
			</span>
		</span>
	</span>
</span>


</x-layout>
