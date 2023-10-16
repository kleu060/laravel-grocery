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
        Schema::create('pns_products', function (Blueprint $table) {
            $table->id();
            $table->text("productId");
            $table->text("productName");
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pns_products');
    }
};
