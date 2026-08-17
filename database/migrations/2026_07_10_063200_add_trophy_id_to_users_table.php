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
            $table->unsignedBigInteger('trophy_id')->nullable()->after('customer_id');

            $table->foreign('trophy_id')->references('id')->on('trophies')->onDelete('set null');
            $table->text('trophy_reward')->nullable()->after('trophy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['trophy_id']);
            $table->dropColumn('trophy_id');
        });
    }
};
