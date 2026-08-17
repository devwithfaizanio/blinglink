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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->enum('subscription_plan', ['free', 'basic', 'premium', 'vip'])->default('free')->after('is_approved');
            $table->integer('recommendation_count')->default(0)->after('subscription_plan');
            $table->timestamp('recommendation_reset_at')->nullable()->after('recommendation_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
