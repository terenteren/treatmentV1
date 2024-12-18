<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drug', function (Blueprint $table) {
            $table->id();
            $table->integer('total_count')->nullable(); // Total Count
            $table->string('item_seq'); // ITEM_SEQ
            $table->string('item_name'); // ITEM_NAME
            $table->string('entp_name'); // ENTP_NAME
            $table->text('description')->nullable(); // 추가설명
            $table->text('chart')->nullable(); // CHART
            $table->string('item_image')->nullable(); // ITEM_IMAGE
            $table->string('item_image_file')->nullable(); // 추가 커스텀 업로드 이미지
            $table->string('class_name')->nullable(); // CLASS_NAME
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_drugs_tables');
    }
};
