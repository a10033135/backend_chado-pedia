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
        if (!Schema::hasTable('ChadoContent_SubCate')) {
            Schema::create('ChadoContent_SubCate', function (Blueprint $table) {
                $table->unsignedBigInteger('chado_content_id');
                $table->unsignedBigInteger('sub_cate_id');

                $table->primary(['chado_content_id', 'sub_cate_id']);
                $table->foreign('chado_content_id')->references('id')->on('ChadoContent')->onDelete('cascade');
                $table->foreign('sub_cate_id')->references('id')->on('SubCate')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ChadoContent_SubCate');
    }
};
