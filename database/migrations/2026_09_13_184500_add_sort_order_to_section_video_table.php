<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSortOrderToSectionVideoTable extends Migration
{
    public function up()
    {
        Schema::table('section_video', function (Blueprint $table) {
            // New movies should naturally land at the end until an admin arranges them.
            $table->unsignedInteger('sort_order')->default(2147483647)->after('video_id');
            $table->index(['section_id', 'sort_order'], 'section_video_section_sort_idx');
        });

        // Preserve the old visual behaviour (newest video first) as the initial order.
        $sectionIds = DB::table('section_video')->select('section_id')->distinct()->pluck('section_id');

        foreach ($sectionIds as $sectionId) {
            $rows = DB::table('section_video')
                ->where('section_id', $sectionId)
                ->orderBy('video_id', 'desc')
                ->get();

            foreach ($rows as $position => $row) {
                DB::table('section_video')
                    ->where('id', $row->id)
                    ->update(['sort_order' => $position + 1]);
            }
        }
    }

    public function down()
    {
        Schema::table('section_video', function (Blueprint $table) {
            $table->dropIndex('section_video_section_sort_idx');
            $table->dropColumn('sort_order');
        });
    }
}
