<?php

use App\Enums\AccountStatusEnum;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('number')->unique();
            $table->string('type');
            $table->string('provider_name');
            $table->string('user_id');
            $table->string('wallet_id');
            $table->string('is_active')->default(AccountStatusEnum::Active->value);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
