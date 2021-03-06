<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInformationAddSortOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->integer('sort_order')->nullable();
            $table->integer('custom_link')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('sort_order','custom_link');
        });
    }
}
