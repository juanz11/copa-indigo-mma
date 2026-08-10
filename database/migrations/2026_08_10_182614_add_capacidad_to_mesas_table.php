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
        Schema::table('mesas', function (Blueprint $table) {
            $table->unsignedInteger('capacidad')->default(8)->after('estado');
        });

        \DB::table('mesas')->whereNull('capacidad')->orWhere('capacidad', 0)->update(['capacidad' => 8]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn('capacidad');
        });
    }
};
