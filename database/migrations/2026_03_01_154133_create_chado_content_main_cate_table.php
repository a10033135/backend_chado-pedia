<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ChadoContent_MainCate', function (Blueprint $table) {
            $table->unsignedBigInteger('chado_content_id');
            $table->unsignedBigInteger('main_cate_id');

            $table->primary(['chado_content_id', 'main_cate_id']);
            $table->foreign('chado_content_id')->references('id')->on('ChadoContent')->onDelete('cascade');
            $table->foreign('main_cate_id')->references('id')->on('MainCate')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ChadoContent_MainCate');
    }
};
