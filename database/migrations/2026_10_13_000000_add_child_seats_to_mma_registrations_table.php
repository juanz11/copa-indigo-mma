<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mma_registrations', function (Blueprint $table) {
            $table->unsignedInteger('child_seats')->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('mma_registrations', function (Blueprint $table) {
            $table->dropColumn('child_seats');
        });
    }
};
