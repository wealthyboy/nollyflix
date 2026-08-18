<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_id');
            $table->string('tx_ref')->unique();
            $table->string('flutterwave_transaction_id')->nullable()->unique();
            $table->string('purchase_type', 10);
            $table->string('currency', 3);
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending')->index();
            $table->text('checkout_url')->nullable();
            $table->json('verification_payload')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}
