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
        Schema::create('faq', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('collection_id');
            $table->boolean('visible')->default(1);
            $table->unsignedBigInteger('position')->default(1);
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections');
        });

        Schema::create('faq_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('faq_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('faq_id')->references('id')->on('faq')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unique(['faq_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_languages');

        Schema::dropIfExists('faq');
    }
};
