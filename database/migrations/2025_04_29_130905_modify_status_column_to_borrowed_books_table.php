<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE borrowed_books CHANGE COLUMN status status ENUM('requested', 'denied', 'borrowed', 'returned', 'reserved') DEFAULT 'requested'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE borrowed_books CHANGE COLUMN status status ENUM('requested', 'denied', 'borrowed', 'returned') DEFAULT 'requested'");
    }
};
