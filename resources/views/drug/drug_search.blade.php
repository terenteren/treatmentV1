<x-layout>
    <style>
        /*고정*/
        .index_head, .index_menu_container { display: none; }
        .container_wrap { padding-bottom: 20px; }
    </style>

    <span class="sub_top">
    <span class="back" onClick="history.go(-1)">
        <img src="{{ asset('img/icon/left_arrow.svg') }}" alt="뒤로가기">
    </span>
    <span class="page_name">약 입력</span>
</span>

    <span class="container_wrap sub_container_wrap">
    <span class="container sub_container sub_container1">
        <span class="search_wrap">
            <span class="search_input_wrap">
                <span class="search_box">
                    <input type="text" name="item_name" id="item_name" placeholder="약물명을 입력해주세요">
                </span>
                <span class="search_btn">
                    <img src="{{ asset('img/icon/search.svg') }}" id="drugSearchProc" alt="검색">
                </span>
            </span>
            <span class="search_result">검색결과 <span>0</span></span>
            <span class="search_list_container">
                {{-- 초기 로딩 데이터 --}}
                @foreach ($drugs as $drug)
                    <span class="search_list_con icon_data"
                          data-item_seq="{{ $drug->item_seq }}"
                          data-item_name="{{ $drug->item_name }}"
                          data-entp_name="{{ $drug->entp_name }}"
                          data-chart="{{ $drug->chart }}"
                          data-item_image="{{ $drug->item_image }}"
                          data-class_name="{{ $drug->class_name }}">
                        <span class="drug_info_wrap">
                            <span class="drug_info_img">
                                <img src="{{ $drug->item_image ?? asset('img/sample/drug.jpg') }}" alt="{{ $drug->item_name }}">
                            </span>
                            <span class="drug_info_box">
                                <span class="drug_info_name">{{ $drug->item_name }}</span>
                                <span class="drug_info_sub">{{ $drug->class_name }}</span>
                            </span>
                        </span>
                    </span>
                @endforeach
            </span>
        </span>
    </span>
</span>

<script>
$(document).ready(function() {
    // 검색 버튼 클릭 이벤트
    $('#drugSearchProc').on('click', function () {
        performSearch();
    });

    // 엔터키 이벤트
    $('#item_name').on('keypress', function (e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const itemName = $('#item_name').val();

        fetch(`/drugSearchProc?item_name=${encodeURIComponent(itemName)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('네트워크 응답에 문제가 있습니다.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const totalCount = data.totalCount || 0;
                $('.search_result span').text(totalCount);

                const items = data.items || [];
                const container = $('.search_list_container');
                container.empty(); // 기존 데이터 초기화

                if (items.length === 0) {
                    container.append('<span>검색 결과가 없습니다.</span>');
                    return;
                }

                items.forEach(item => {
                    const template = `
                    <span class="search_list_con"
                          data-item_seq="${item.item_seq}"
                          data-item_name="${item.item_name}"
                          data-entp_name="${item.entp_name}"
                          data-chart="${item.chart}"
                          data-item_image="${item.item_image}"
                          data-class_name="${item.class_name}">
                        <span class="drug_info_wrap">
                            <span class="drug_info_img">
                                <img src="${item.item_image || '{{ asset('img/sample/drug.jpg') }}'}" alt="${item.item_name}">
                            </span>
                            <span class="drug_info_box">
                                <span class="drug_info_name">${item.item_name}</span>
                                <span class="drug_info_sub">${item.class_name}</span>
                            </span>
                        </span>
                    </span>
                `;
                    container.append(template);
                });

                // 로컬 스토리지 저장 이벤트 바인딩
                $('.search_list_con').on('click', function () {
                    const drugData = {
                        item_seq: $(this).data('item_seq'),
                        item_name: $(this).data('item_name'),
                        entp_name: $(this).data('entp_name'),
                        chart: $(this).data('chart'),
                        item_image: $(this).data('item_image'),
                        class_name: $(this).data('class_name')
                    };

                    // 로컬 스토리지에 저장
                    localStorage.setItem('selectedDrug', JSON.stringify(drugData));
                    alert('약물이 저장되었습니다: ' + drugData.item_name);
                });
            })
            .catch(error => {
                console.error('오류 발생:', error);
                alert('검색 중 오류가 발생했습니다.');
            });
    }

    // 약물 선택 시 로컬스토리지에 저장
    $(document).on('click', '.icon_data', function () {
        const currentIndex = localStorage.getItem('currentDrugIndex'); // 현재 선택된 인덱스
        const selectedDrug = {
            item_name: $(this).data('item_name'),
            item_seq: $(this).data('item_seq')
        };

        // 로컬스토리지에서 기존 데이터를 가져오기
        const drugDataList = JSON.parse(localStorage.getItem('drugDataList')) || [];

        if (currentIndex) {
            const index = currentIndex - 1; // 인덱스는 1부터 시작하므로 0 기반 배열로 변환

            // 기존 데이터가 있으면 약물 정보만 업데이트
            if (drugDataList[index]) {
                drugDataList[index].item_name = selectedDrug.item_name;
                drugDataList[index].item_seq = selectedDrug.item_seq;
            } else {
                // 기존 데이터가 없으면 새로운 데이터 추가
                drugDataList[index] = {
                    ...selectedDrug,
                    dosage: null,
                    start_date: null,
                    drug_take_time: null,
                    drug_take_date: null
                };
            }
        } else {
            // 인덱스가 없는 경우 새 데이터 추가
            drugDataList.push({
                ...selectedDrug,
                dosage: null,
                start_date: null,
                drug_take_time: null,
                drug_take_date: null
            });
        }

        // 업데이트된 데이터를 로컬스토리지에 저장
        localStorage.setItem('drugDataList', JSON.stringify(drugDataList));

        // 이전 페이지로 돌아가기
        window.location.href = "{{ route('signup5') }}";
    });


});
</script>
</x-layout>
