<?php

use App\Enums\WalletLimitedStatusEnum;
use App\Enums\WalletLockedStatusEnum;
use App\Enums\WalletTypeEnum;
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
        Schema::create('wallets', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->decimal('balance')->default(0);
            $table->string('country_id');
            $table->integer('is_limited')->default(WalletLimitedStatusEnum::False->value);
            $table->integer('is_locked')->default(WalletLockedStatusEnum::False->value);
            $table->string('wallet_type_id');
            $table->string('matured_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('wallet_type_id')->references('id')->on('wallet_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
