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
        Schema::create('event_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->boolean('visible')->default(1);
            $table->unsignedSmallInteger('position')->default(1);
            $table->timestamps();

            $table->foreign('event_id')->references('id')
                ->on('events')
                ->onDelete('cascade');
        });

        Schema::create('event_file_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_file_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('file');
            $table->timestamps();

            $table->unique(['event_file_id', 'language_id']);
            $table->foreign('event_file_id')->references('id')
                ->on('event_files')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_file_languages');
        Schema::dropIfExists('event_files');
    }
};
