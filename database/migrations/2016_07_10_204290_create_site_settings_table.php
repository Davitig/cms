<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->tinyInteger('id', true);
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('google_plus')->nullable();
            $table->string('map', 800)->nullable();
            $table->timestamps();
        });

        // Insert default row.
        // Seeding in migration, because of triggers constraint.
        DB::table('site_settings')->insert([[]]);

        // Create triggers
        DB::unprepared(
'CREATE TRIGGER `site_settings_insert_not_allowed` BEFORE INSERT ON `site_settings`
FOR EACH ROW BEGIN
    SIGNAL SQLSTATE "45000"
    SET MESSAGE_TEXT = "insert not allowed";
END'
);
        DB::unprepared(
'CREATE TRIGGER `site_settings_delete_not_allowed` BEFORE DELETE ON `site_settings`
FOR EACH ROW BEGIN
    SIGNAL SQLSTATE "45000"
    SET MESSAGE_TEXT = "delete not allowed";
END'
);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_settings');
    }
}