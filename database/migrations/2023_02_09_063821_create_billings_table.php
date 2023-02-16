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
        Schema::create('billings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('monthly_journal_id')->index();
            $table->foreign('monthly_journal_id')->references('id')->on('monthly_journals')->onUpdate('cascade')->onDelete('cascade');

            $table->datetime('transaction_time')->nullable();
            $table->datetime('transaction_due_time')->nullable();

            $table->foreignUuid('payment_term_id')->index();
            $table->foreign('payment_term_id')->references('id')->on('payment_terms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('supplier_address_id')->index();
            $table->foreign('supplier_address_id')->references('id')->on('contacts')->onUpdate('cascade')->onDelete('cascade');

            $table->string('code')->nullable();
            $table->string('internal_code')->nullable();

            $table->text('note')->nullable();

            $table->double('subtotal', 50, 10)->default(0);
            $table->double('total_discount', 50, 10)->default(0);
            $table->double('total_shipping', 50, 10)->default(0);
            $table->double('total_shipping_discount', 50, 10)->default(0);
            $table->double('total_tax', 50, 10)->default(0);
            $table->double('total_tax_value', 50, 10)->default(0);
            $table->string('total_tax_type', 50, 10)->default('Percent');
            $table->double('total_bill', 50, 10)->default(0);
            $table->double('total_amount_paid', 50, 10)->default(0);
            $table->double('total_due_balance', 50, 10)->default(0);

            $table->foreignUuid('status_id')->index();
            $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('billings');
    }
};
