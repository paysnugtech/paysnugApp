<?php

use App\Enums\VerificationEnum;
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
        Schema::create('verifications', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('level')->default(0);
            $table->string('user_id');
            $table->integer('attempt')->default(0);
            $table->integer('email_verified')->default(VerificationEnum::NotVerified->value);
            $table->integer('phone_verified')->default(VerificationEnum::NotVerified->value);
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('verifications');
    }
};
