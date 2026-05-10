<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeriesSupportToVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'content_type')) {
                $table->string('content_type', 20)->default('movie')->after('title')->index();
            }
        });

        if (!Schema::hasTable('video_episodes')) {
            Schema::create('video_episodes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('video_id');
                $table->unsignedInteger('season_number')->default(1);
                $table->unsignedInteger('episode_number')->nullable();
                $table->string('title')->nullable();
                $table->string('duration')->nullable();
                $table->text('link')->nullable();
                $table->text('preview_link')->nullable();
                $table->text('iframe')->nullable();
                $table->string('track_file')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
                $table->index(['video_id', 'season_number', 'episode_number']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_episodes');

        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'content_type')) {
                $table->dropColumn('content_type');
            }
        });
    }
}