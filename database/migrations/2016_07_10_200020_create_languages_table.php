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
        Schema::create('languages', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->char('language', 2)->unique();
            $table->unsignedTinyInteger('visible')->default(0);
            $table->unsignedTinyInteger('position')->default(1);
            $table->unsignedTinyInteger('main')->default(0);
            $table->string('full_name', 32);
            $table->string('short_name', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
