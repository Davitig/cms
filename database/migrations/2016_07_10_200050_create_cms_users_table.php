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
        Schema::create('cms_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->unsignedTinyInteger('cms_user_role_id');
            $table->string('first_name', 35);
            $table->string('last_name', 35);
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->boolean('blocked')->default(0);
            $table->string('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('cms_user_role_id')->references('id')
                ->on('cms_user_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_users');
    }
};
