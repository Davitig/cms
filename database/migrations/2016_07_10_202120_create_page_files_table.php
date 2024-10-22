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
        Schema::create('page_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id');
            $table->boolean('visible')->default(1);
            $table->unsignedSmallInteger('position')->default(1);
            $table->timestamps();

            $table->foreign('page_id')->references('id')
                ->on('pages')->onDelete('cascade');
        });

        Schema::create('page_file_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_file_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('file');
            $table->timestamps();

            $table->unique(['page_file_id', 'language_id']);
            $table->foreign('page_file_id')->references('id')
                ->on('page_files')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_file_languages');

        Schema::dropIfExists('page_files');
    }
};
