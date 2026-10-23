<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceDetailsToMobileApiLogs extends Migration
{
    public function up()
    {
        Schema::table('mobile_api_logs', function (Blueprint $table) {
            $table->string('device_brand', 80)->nullable();
            $table->string('device_model', 120)->nullable();
            $table->string('os_version', 40)->nullable();
        });
    }

    public function down()
    {
        Schema::table('mobile_api_logs', function (Blueprint $table) {
            $table->dropColumn(['device_brand', 'device_model', 'os_version']);
        });
    }
}
