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
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_user_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('color', 16)->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->timestamps();

            $table->foreign('cms_user_id')->references('id')
                ->on('cms_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar');
    }
};
