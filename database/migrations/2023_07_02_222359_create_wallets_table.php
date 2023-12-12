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
            $table->decimal('balance')->default(0);
            $table->boolean('is_limited')->default(WalletLimitedStatusEnum::True->value);
            $table->boolean('is_locked')->default(WalletLockedStatusEnum::False->value);
            $table->boolean('is_inflow_allow')->default(true);
            $table->boolean('is_outflow_allow')->default(true);
            $table->boolean('is_interest')->default(false);
            $table->string('matured_at')->nullable();
            $table->string('user_id');
            $table->string('country_id');
            $table->string('wallet_type_id');
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
