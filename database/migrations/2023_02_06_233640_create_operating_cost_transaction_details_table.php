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
        Schema::create('operating_cost_transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('operating_cost_transaction_id')->index('operating_cost_transaction_id_index');
            $table->foreign('operating_cost_transaction_id', 'fk_operating_cost_transaction_id')->references('id')->on('operating_cost_transactions')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignUuid('operating_cost_id')->index();
            $table->foreign('operating_cost_id')->references('id')->on('operating_costs')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('operating_cost_transaction_details');
    }
};
