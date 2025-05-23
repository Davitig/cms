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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('cms_user_role_id');
            $table->string('route_name');
            $table->timestamps();

            $table->unique(['cms_user_role_id', 'route_name']);
            $table->foreign('cms_user_role_id')->references('id')
                ->on('cms_user_roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
