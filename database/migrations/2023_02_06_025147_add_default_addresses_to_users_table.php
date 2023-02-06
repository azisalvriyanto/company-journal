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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('default_contact_address_id')->nullable()->index()->after('parent_company_id');
            $table->foreign('default_contact_address_id')->references('id')->on('contacts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('default_billing_address_id')->nullable()->index()->after('default_contact_address_id');
            $table->foreign('default_billing_address_id')->references('id')->on('contacts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('default_shipping_address_id')->nullable()->index()->after('default_billing_address_id');
            $table->foreign('default_shipping_address_id')->references('id')->on('contacts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign([
                'default_contact_address_id',
                'default_billing_address_id',
                'default_shipping_address_id',
            ]);
            $table->dropColumn('default_contact_address_id');
            $table->dropColumn('default_billing_address_id');
            $table->dropColumn('default_shipping_address_id');
        });
    }
};
