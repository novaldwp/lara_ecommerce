<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('address_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->bigInteger('base_price');
            $table->integer('shipping_cost');
            $table->bigInteger('total_price');
            $table->string('shipping_courier');
            $table->string('shipping_service');
            $table->string('airway_bill')->nullable();
            $table->timestamp('order_date');
            $table->dateTime('payment_due')->nullable();
            $table->timestamp('confirm_at')->nullable();
            $table->foreignId('confirm_by')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->foreignId('shipped_by')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('confirm_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('shipped_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
