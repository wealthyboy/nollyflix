<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->string('site_name');
			$table->string('address');
			$table->string('site_email');
			$table->string('site_phone')->nullable();
			$table->string('image')->nullable();
			$table->string('meta_title')->nullable();
			$table->text('meta_description')->nullable();
			$table->text('meta_tag_keywords')->nullable();
			$table->integer('items_per_page')->length(11)->nullable();
            $table->string('alert_email');
            $table->boolean('allow_multi_currency')->default(false);
            $table->boolean('allow_multiple_logins')->default(true);
            $table->integer('logins')->default(1);
            $table->integer('currency_id')->default(1);
			$table->string('invoice_prefix')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('youtube_link')->nullable();
			$table->string('store_logo',200)->nullable();
			$table->string('store_icon',200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}
