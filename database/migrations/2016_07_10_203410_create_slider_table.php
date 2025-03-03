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
        Schema::create('slider', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('position')->default(1);
            $table->string('file', 800)->nullable();
            $table->string('link', 800)->nullable();
            $table->timestamps();
        });

        Schema::create('slider_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('slider_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->string('description', 800)->nullable();
            $table->timestamps();

            $table->unique(['slider_id', 'language_id']);
            $table->foreign('slider_id')->references('id')
                ->on('slider')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_languages');
        Schema::dropIfExists('slider');
    }
};
