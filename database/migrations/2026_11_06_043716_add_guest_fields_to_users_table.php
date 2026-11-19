<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country', 50)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location', 100)->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'risk_level')) {
                $table->enum('risk_level', ['low', 'medium', 'high'])->default('low')->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country', 'location', 'risk_level']);
        });
    }
};