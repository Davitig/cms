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
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->boolean('visible')->default(0);
            $table->unsignedSmallInteger('position')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onDelete('cascade');
        });

        Schema::create('product_file_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_file_id');
            $table->unsignedSmallInteger('language_id');
            $table->string('title');
            $table->string('file');
            $table->timestamps();

            $table->unique(['product_file_id', 'language_id']);
            $table->foreign('product_file_id')->references('id')
                ->on('product_files')
                ->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_file_languages');
        Schema::dropIfExists('product_files');
    }
};
