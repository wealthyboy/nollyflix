<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVideosTableAddIsFree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->boolean('is_free')->default(false);
            $table->boolean('is_only_for_rent')->default(false);
            $table->boolean('is_only_for_buy')->default(false);
            $table->boolean('is_for_rent_and_buy')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            Schema::dropIfExists('is_free','is_only_for_rent','is_only_for_buy','is_for_rent_and_buy');
        });
    }
}
