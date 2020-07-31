<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('uid')->nullable();
            $table->string('title')->nullable();
            $table->string('file_name')->nullable();
            $table->string('duration')->nullable();
            $table->string('film_rating')->nullable();
            $table->string('resolution')->nullable();
            $table->string('poster')->nullable();
            $table->string('tn_poster')->nullable();
            $table->string('link')->nullable();
            $table->string('preview_link')->nullable();
            $table->string('preview_video_id')->nullable();
            $table->text('description')->nullable();
            $table->text('video_id')->nullable();
            $table->string('iframe')->nullable();
            $table->boolean('processed')->default(false);
            $table->boolean('allow_restriction')->default(false);
            $table->boolean('allow_rent')->default(true);
            $table->boolean('allow_buy')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamps();
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
        Schema::dropIfExists('videos');
    }
}
