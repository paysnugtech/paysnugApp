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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id');
            $table->string('number');
            $table->decimal('amount');
            $table->decimal('commission')->default(0);
            $table->decimal('discount')->default(0);
            $table->decimal('profit')->default(0);
            $table->decimal('balance_before',16);
            $table->decimal('balance_after',16);
            $table->string('reference_no');
            $table->integer('type');
            $table->integer('service_type');
            $table->string('narration')->nullable();
            $table->integer('status');
            $table->string('remark');
            $table->string('user_id');
            $table->string('updated_by')->nullable();
            $table->bigIncrements('order_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            
            //auto increment needs to be an index (not necessarily primary) 
            $table->index(array("order_by")); 

            //after setting "normal" index, drop primary 
            $table->dropPrimary(); 
            
            //add primary to the field you want 
            $table->primary("id");
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
