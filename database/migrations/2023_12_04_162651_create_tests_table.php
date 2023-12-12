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
        Schema::create('tests', function (Blueprint $table) {
            $table->string("id"); 
            // $table->increments("order_by"); 
            $table->bigIncrements("order_by"); 
            
            $table->timestamps();

            
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
        Schema::dropIfExists('tests');
    }
};
