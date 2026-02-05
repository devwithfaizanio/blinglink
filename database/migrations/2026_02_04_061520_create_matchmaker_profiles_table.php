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
        Schema::create('matchmaker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();


            // Matchmaking Info
            $table->integer('experience_years')->nullable();
            $table->json('matchmaking_type')->nullable();
            $table->integer('preferred_age_min')->nullable();
            $table->integer('preferred_age_max')->nullable();
            $table->enum('preferred_gender', ['male','female','both'])->nullable();
            $table->json('coverage_area')->nullable();

            // Credibility
            $table->integer('total_matches')->default(0);
            $table->text('success_story')->nullable();

            // Verification (Admin only)
            $table->string('id_document')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();

            // Admin Notes
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchmaker_profiles');
    }
};
