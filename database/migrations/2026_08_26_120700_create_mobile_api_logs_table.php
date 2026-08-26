<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileApiLogsTable extends Migration
{
    public function up()
    {
        Schema::create('mobile_api_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('request_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('method', 10)->index();
            $table->string('path', 255)->index();
            $table->unsignedSmallInteger('status')->nullable()->index();
            $table->decimal('duration_ms', 10, 2)->nullable()->index();
            $table->string('ip', 64)->nullable();
            $table->string('platform', 30)->nullable()->index();
            $table->string('app_version', 40)->nullable()->index();
            $table->string('app_build', 40)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('request_payload')->nullable();
            $table->mediumText('response_payload')->nullable();
            $table->text('exception')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            $table->index(['created_at', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_api_logs');
    }
}
