<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 18)->unique();
            $table->string('title');
            $table->string('type', 32)->nullable();
            $table->timestamps();
        });

        Schema::create('translation_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('translation_id');
            $table->char('language', 2);
            $table->string('value');
            $table->timestamps();

            $table->foreign('translation_id')
                ->references('id')
                ->on('translations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translation_languages');

        Schema::dropIfExists('translations');
    }
};
