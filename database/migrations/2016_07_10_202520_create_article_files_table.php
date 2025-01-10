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
        Schema::create('article_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->boolean('visible')->default(1);
            $table->unsignedSmallInteger('position')->default(1);
            $table->timestamps();

            $table->foreign('article_id')->references('id')
                ->on('articles')
                ->onDelete('cascade');
        });

        Schema::create('article_file_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_file_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('file');
            $table->timestamps();

            $table->unique(['article_file_id', 'language_id']);
            $table->foreign('article_file_id')->references('id')
                ->on('article_files')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_file_languages');

        Schema::dropIfExists('article_files');
    }
};
