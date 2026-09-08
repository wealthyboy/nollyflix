<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureTrackFileOnVideoEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('video_episodes') && !Schema::hasColumn('video_episodes', 'track_file')) {
            Schema::table('video_episodes', function (Blueprint $table) {
                $table->string('track_file')->nullable()->after('iframe');
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
        // Intentionally left in place because track_file may pre-date this safety migration.
    }
}
