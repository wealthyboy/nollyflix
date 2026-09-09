<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureContentOwnerIdOnOrdersTable extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('orders', 'content_owner_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('content_owner_id')->nullable()->after('user_id');
            });
        }
    }

    public function down()
    {
        // This column existed in older Nollyflix installations. Do not remove it
        // when rolling back this compatibility migration.
    }
}
