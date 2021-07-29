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
            $table->foreignId('member_id')->nullable();
            $table->foreignId('address_id')->nullable();
            $table->string('courier');
            $table->string('courier_service');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('set null');
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
