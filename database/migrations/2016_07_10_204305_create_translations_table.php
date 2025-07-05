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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 18)->unique();
            $table->string('type', 32)->nullable();
            $table->timestamps();
        });

        Schema::create('translation_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('translation_id');
            $table->unsignedTinyInteger('language_id');
            $table->string('value');
            $table->timestamps();

            $table->unique(['translation_id', 'language_id']);
            $table->foreign('translation_id')->references('id')
                ->on('translations')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_languages');
        Schema::dropIfExists('translations');
    }
};
