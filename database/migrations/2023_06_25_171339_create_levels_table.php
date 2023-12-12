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
        Schema::create('levels', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('code');
            $table->string('description')->nullable();
            $table->decimal('daily_limit');
            $table->decimal('daily_unused')->default(0.00);
            $table->decimal('monthly_limit');
            $table->decimal('monthly_unused')->default(0.00);
            $table->decimal('balance')->default(300000.00);
            $table->string('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
