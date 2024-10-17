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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id');
            $table->string('slug')->unique();
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('position')->default(1);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('type', 64)->default('page');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('template', 64)->nullable();
            $table->boolean('collapse')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus');
        });

        Schema::create('page_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('short_title');
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_desc')->nullable();
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unique(['page_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_languages');

        Schema::dropIfExists('pages');
    }
};
