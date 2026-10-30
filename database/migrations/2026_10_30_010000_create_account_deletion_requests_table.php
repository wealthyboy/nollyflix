<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDeletionRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('account_deletion_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('reference')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->timestamp('confirmed_at');
            $table->timestamps();
            // Preserve the request reference if an operator later removes the account.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_deletion_requests');
    }
}
