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
        Schema::create('pns_carts', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger("product_id")->nullable()->unsigned();
            $table->double('quantity', 8, 2);
            $table->timestamps();
        });

        Schema::table('pns_carts', function (Blueprint $table) 
        {
            $table->foreign('product_id')->references('id')->on('pns_products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pns_carts');
    }
};
