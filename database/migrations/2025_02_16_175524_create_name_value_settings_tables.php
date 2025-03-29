<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tableNames = [
        // strings
        'web_settings' => 'string',
    ];

    protected array $languageTableNames = [];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tableNames as $tableName => $columnType) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) use ($tableName, $columnType) {
                    $table->smallIncrements('id');

                    if ($hasLanguage = in_array($tableName, $this->languageTableNames)) {
                        $table->unsignedTinyInteger('language_id');
                    }

                    $table->string('name');
                    $table->$columnType('value')->nullable();
                    $table->timestamps();

                    if ($hasLanguage) {
                        $table->unique(['language_id', 'name']);

                        $table->foreign('language_id')->references('id')->on('languages');
                    } else {
                        $table->unique(['name']);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tableNames as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
