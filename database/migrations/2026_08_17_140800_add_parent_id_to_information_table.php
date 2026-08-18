<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('information', 'parent_id')) {
            Schema::table('information', function (Blueprint $table) {
                $table->unsignedInteger('parent_id')->nullable()->after('id');
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
        if (Schema::hasColumn('information', 'parent_id')) {
            Schema::table('information', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
}
