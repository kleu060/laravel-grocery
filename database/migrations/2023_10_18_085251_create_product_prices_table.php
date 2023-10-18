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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("pns_product_id")->nullable()->unsigned();
            $table->text("PriceMode");
            $table->text("PricePerItem");
            $table->text("HasMultiBuyDeal");
            $table->text("MultiBuyDeal");
            $table->text("PricePerBaseUnitText");
            $table->text("MultiBuyBasePrice");
            $table->text("MultiBuyPrice");
            $table->text("MultiBuyQuantity");
            $table->text("PromoBadgeImageLabel");
            $table->timestamps();
        });

        Schema::table('product_prices', function (Blueprint $table) 
        {
            $table->foreign('pns_product_id')->references('id')->on('pns_products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
