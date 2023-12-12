<?php

use App\Enums\ServiceStatusEnum;
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
        Schema::create('bank_beneficiaries', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('account_name');
            $table->string('account_no');
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('user_id');
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_beneficiaries');
    }
};
