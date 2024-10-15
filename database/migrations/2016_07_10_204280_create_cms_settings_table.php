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
            $table->unsignedBigInteger('cms_user_id');
            $table->string('sidebar_position', 64)->default('fixed');
            $table->string('sidebar_direction', 64)->default('left-sidebar');
            $table->boolean('horizontal_menu')->default(1);
            $table->string('horizontal_menu_minimal', 64)
                ->default('navbar-minimal')
                ->nullable();
            $table->string('horizontal_menu_click', 64)->nullable();
            $table->string('skin_sidebar', 64)->nullable();
            $table->string('skin_user_menu', 64)->nullable();
            $table->string('skin_horizontal', 64)->nullable();
            $table->string('skin_login', 64)->nullable();
            $table->string('layout_boxed', 64)->nullable();
            $table->string('alert_position', 64)->default('top-right');
            $table->string('ajax_form', 64)->default('ajax-form');
            $table->string('lockscreen')->default('0');
            $table->timestamps();

            $table->foreign('cms_user_id')->references('id')->on('cms_users')->onDelete('cascade');
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
