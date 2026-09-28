<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationTables extends Migration
{
    public function up()
    {
        Schema::create('push_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->char('token_hash', 64)->unique();
            $table->text('token');
            $table->string('platform', 20)->nullable();
            $table->string('device_name', 120)->nullable();
            $table->string('app_version', 40)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'active']);
        });

        Schema::create('push_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('title');
            $table->text('body');
            $table->text('data')->nullable();
            $table->string('status', 30)->default('queued')->index();
            $table->unsignedInteger('recipients_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->text('failure_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('push_campaign_video', function (Blueprint $table) {
            $table->unsignedBigInteger('push_campaign_id');
            $table->unsignedBigInteger('video_id');
            $table->unique(['push_campaign_id', 'video_id'], 'push_campaign_video_unique');
            $table->index('video_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('push_campaign_video');
        Schema::dropIfExists('push_campaigns');
        Schema::dropIfExists('push_devices');
    }
}
