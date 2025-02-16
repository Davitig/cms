<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tableNames = [
        // strings
        'web_settings' => 'string'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tableNames as $tableName => $columnType) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) use ($columnType) {
                    $table->smallIncrements('id');
                    $table->string('name')->unique();
                    $table->$columnType('value')->nullable();
                    $table->timestamps();
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
