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
        if (!Schema::hasTable('SubCate')) {
            Schema::create('SubCate', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('main_cate_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('sort')->default(0);
                $table->boolean('has_image')->default(false);
                $table->boolean('enable')->default(true);
                $table->dateTime('create_time')->nullable();
                $table->dateTime('update_time')->nullable();

                $table->foreign('main_cate_id')->references('id')->on('MainCate')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SubCate');
    }
};
