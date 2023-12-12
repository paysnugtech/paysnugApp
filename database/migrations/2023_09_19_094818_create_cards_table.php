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
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('number')->nullable();
            $table->string('front_url')->nullable();
            $table->string('back_url')->nullable();
            $table->tinyInteger('is_verified')->default(VerificationEnum::NotVerified->value);
            $table->string('remark')->nullable();
            $table->string('user_id');
            $table->string('verification_id');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verification_id')->references('id')->on('verifications')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
