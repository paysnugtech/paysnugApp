<?php

use App\Enums\EmailNotificationEnum;
use App\Enums\PushNotificationEnum;
use App\Enums\SmsNotificationEnum;
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('is_email')->default(EmailNotificationEnum::True->value);
            $table->integer('is_push')->default(PushNotificationEnum::True->value);
            $table->integer('is_sms')->default(SmsNotificationEnum::False->value);
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
        Schema::dropIfExists('notifications');
    }
};
