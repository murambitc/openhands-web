<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_checkins', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name', 100);
            $table->string('mood', 100);
            $table->text('comment');
            $table->timestamps();

            $table->index(['guest_name']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_checkins');
    }
};