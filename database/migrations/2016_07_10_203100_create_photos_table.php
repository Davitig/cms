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
        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gallery_id');
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('position')->default(1);
            $table->string('file', 800)->nullable();
            $table->timestamps();

            $table->foreign('gallery_id')->references('id')->on('galleries');
        });

        Schema::create('photo_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('photo_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->timestamps();

            $table->unique(['photo_id', 'language_id']);
            $table->foreign('photo_id')->references('id')
                ->on('photos')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_languages');

        Schema::dropIfExists('photos');
    }
};
