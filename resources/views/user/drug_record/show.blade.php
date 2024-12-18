<x-layout>


<h1>약물 기록 상세 정보</h1>
<p><strong>약물명:</strong> {{ $drugRecord->drug->item_name ?? '알 수 없음' }}</p>
<p><strong>제조사:</strong> {{ $drugRecord->drug->entp_name ?? '알 수 없음' }}</p>
<p><strong>복용량:</strong> {{ $drugRecord->dosage }}</p>
<p><strong>복용 시작일:</strong> {{ $drugRecord->start_date }}</p>

    <p><strong>복용 시간:</strong></p>
    <ul>
        @foreach(explode(',', $drugRecord->drug_take_time) as $time)
            <li>{{ trim($time) }}</li>
        @endforeach
    </ul>

    <p><strong>복용 날짜:</strong></p>
    <ul>
        @foreach(explode(',', $drugRecord->drug_take_date) as $date)
            <li>{{ trim($date) }}</li>
        @endforeach
    </ul>

<a href="{{ route('index') }}">뒤로가기</a>

<script>
$(document).ready(function() {

});
</script>
</x-layout>
