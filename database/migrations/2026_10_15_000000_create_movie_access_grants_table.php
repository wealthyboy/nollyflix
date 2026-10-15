<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieAccessGrantsTable extends Migration
{
    public function up()
    {
        Schema::create('movie_access_grants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('grant_token')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('granted_by')->nullable();
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('order_id');
            $table->string('purchase_type', 10);
            $table->boolean('order_created')->default(false);
            $table->text('reason');
            $table->string('payment_reference', 128)->nullable();
            $table->timestamp('email_sending_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->text('email_error')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('granted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_access_grants');
    }
}
