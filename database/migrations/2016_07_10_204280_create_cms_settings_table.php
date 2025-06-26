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
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->tinyInteger('id', true);
            $table->unsignedBigInteger('cms_user_id')->unique();
            $table->boolean('horizontal_menu')->default(0);
            $table->string('role_view')->nullable();
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
        Schema::dropIfExists('cms_settings');
    }
};
