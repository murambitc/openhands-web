<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('age_group', 50)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('pronouns', 50)->nullable();
            $table->string('orientation', 50)->nullable();
            $table->string('counselor_preference', 20)->nullable();

            $table->string('therapist_history', 20)->nullable();
            $table->string('main_concern', 100)->nullable();
            $table->string('mental_rating', 20)->nullable();
            $table->string('medication', 20)->nullable();

            $table->string('support_system', 20)->nullable();
            $table->string('substance_use', 20)->nullable();
            $table->string('sleep', 50)->nullable();

            $table->string('self_harm', 20)->nullable();
            $table->string('danger', 20)->nullable();
            $table->string('support_need', 50)->nullable();

            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');

            $table->foreignId('preferred_referral')->nullable()
                  ->constrained('organizations')->nullOnDelete();

            $table->timestamps();

            $table->index(['risk_level']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};