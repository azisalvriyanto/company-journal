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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->index();
            $table->foreign('owner_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('item_category_id')->index();
            $table->foreign('item_category_id')->references('id')->on('item_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('item_code')->nullable();
            $table->foreignUuid('unit_of_measurement_id')->index();
            $table->foreign('unit_of_measurement_id')->references('id')->on('unit_of_measurements')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image_url')->nullable();
            $table->string('detail_group')->nullable();
            $table->boolean('is_enable')->default(1);
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
        Schema::dropIfExists('items');
    }
};
