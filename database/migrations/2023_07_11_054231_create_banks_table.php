<?php

use App\Enums\BankStatusEnum;
use App\Enums\UserStatusEnum;
use App\Enums\VirtualAccountEnum;
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
        Schema::create('banks', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->unique();
            $table->string('bank_code')->unique();
            $table->string('address');
            $table->string('type');
            $table->integer('is_active')->default(BankStatusEnum::Active->value);
            $table->integer('is_virtual_account')->default(VirtualAccountEnum::False->value);
            $table->string('country_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
