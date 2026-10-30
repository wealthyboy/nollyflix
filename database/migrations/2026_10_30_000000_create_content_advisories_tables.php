<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentAdvisoriesTables extends Migration
{
    public function up()
    {
        Schema::create('content_advisories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('content_advisory_video', function (Blueprint $table) {
            $table->unsignedBigInteger('content_advisory_id');
            $table->unsignedBigInteger('video_id');
            $table->timestamps();
            $table->primary(['content_advisory_id', 'video_id']);
            $table->foreign('content_advisory_id')->references('id')->on('content_advisories')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('content_advisory_video');
        Schema::dropIfExists('content_advisories');
    }
}
