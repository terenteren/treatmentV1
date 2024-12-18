<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('child_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 유저와 관계
            $table->foreignId('child_id')->constrained()->onDelete('cascade'); // 아이와 관계
            $table->string('kind'); // 치료 종류
            $table->string('kind_etc'); // 치료 종류 기타 입력
            $table->string('clinic_name'); // 치료실 이름
            $table->date('start_date'); // 시작 날짜
            $table->string('treatment_frequency'); // 치료 빈도
            $table->string('monthly_cost'); // 월 비용
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_treatments');
    }
};
