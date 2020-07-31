<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVideosTableAddPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->decimal('buy_price',8,2)->nullable();
            $table->decimal('rent_price',8,2)->nullable();
            $table->timestamp('release_date')->nullable();
            $table->string('slug')->nullable();

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
            $table->dropColumn('buy_price');
            $table->dropColumn('rent_price');
            $table->dropColumn('release_date');
            $table->dropColumn('slug');
        });
    }
}
