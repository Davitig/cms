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
        Schema::create('galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('collection_id');
            $table->string('slug')->unique();
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('position')->default(1);
            $table->string('type', 32);
            $table->string('admin_order_by', 32)->default('id');
            $table->string('admin_sort', 16)->default('desc');
            $table->boolean('admin_per_page')->default(20);
            $table->string('web_order_by', 32)->default('id');
            $table->string('web_sort', 16)->default('desc');
            $table->boolean('web_per_page')->default(10);
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections');
        });

        Schema::create('gallery_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gallery_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('description', 800)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_desc')->nullable();
            $table->timestamps();

            $table->unique(['gallery_id', 'language_id']);
            $table->foreign('gallery_id')->references('id')
                ->on('galleries')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_languages');
        Schema::dropIfExists('galleries');
    }
};
