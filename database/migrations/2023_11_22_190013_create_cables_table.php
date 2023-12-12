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
        Schema::create('cables', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->decimal('amount', 16);
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('provider_name');
            $table->string('description');
            $table->string('transaction_id');
            $table->string('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cables');
    }
};
