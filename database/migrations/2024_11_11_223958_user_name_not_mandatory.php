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
        $schema = Schema::connection($this->getConnection());

        $schema->table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());

        $schema->table('users', function (Blueprint $table) {
            // Revert the 'name' column back to not nullable
            $table->string('name')->nullable(false)->change();
        });
    }
};
