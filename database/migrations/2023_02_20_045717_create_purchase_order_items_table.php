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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_order_id')->index('purchase_order_id_index');
            $table->foreign('purchase_order_id', 'fk_purchase_order_id')->references('id')->on('purchase_orders')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('purchase_order_items');
    }
};
