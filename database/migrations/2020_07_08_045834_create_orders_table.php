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
            $table->timestamps();
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('invoice');
			$table->string('comment')->nullable();
			$table->string('payment_type')->nullable();
			$table->string('status')->nullable();
			$table->string('transaction_id')->nullable();
            $table->integer('currency_id')->length(10);
            $table->string('currency')->nullable();
			$table->string('total')->nullable();
			$table->string('coupon',128)->unique()->nullable();
			$table->string('purchase_type')->nullable();
			$table->string('ip')->nullable();
			$table->string('user_agent')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
