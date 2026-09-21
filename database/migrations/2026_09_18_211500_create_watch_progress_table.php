<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchProgressTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('watch_progress')) {
            return;
        }

        Schema::create('watch_progress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('episode_id')->nullable();
            $table->unsignedInteger('position_seconds')->default(0);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'video_id'], 'watch_progress_user_video_unique');
            $table->index(['user_id', 'completed', 'last_watched_at'], 'watch_progress_user_status_idx');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('episode_id')->references('id')->on('video_episodes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('watch_progress');
    }
}
