<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sales_order_id')->index('sales_order_id_index');
            $table->foreign('sales_order_id', 'fk_sales_order_id')->references('id')->on('sales_orders')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignUuid('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade')->onDelete('cascade');

            $table->double('quantity', 50, 10)->default(0);
            $table->double('price', 50, 10)->default(0);
            $table->double('total_price', 50, 10)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
};
