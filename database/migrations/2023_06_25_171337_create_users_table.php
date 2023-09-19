<?php

use App\Enums\BillVerificationEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('level')->default(1);
            $table->string('finger_print')->nullable();
            $table->string('pin')->nullable();
            $table->string('role_id');
            $table->string('manager_id');
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();

            
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('manager_id')->references('id')->on('managers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
