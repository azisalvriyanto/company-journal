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
        Schema::create('storage_operation_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('storage_id')->index();
            $table->foreign('storage_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('operation_type_id')->index();
            $table->foreign('operation_type_id')->references('id')->on('operation_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('prefix_format')->nullable();
            $table->string('suffix_format')->nullable();
            $table->integer('sequence_size')->default(10);
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
        Schema::dropIfExists('storage_operation_types');
    }
};
