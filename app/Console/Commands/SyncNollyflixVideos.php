<?php

namespace App\Console\Commands;

use App\Video;
use App\Category;
use App\Genre;
use App\Section;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Move catalogue metadata between NollyFlix installations without touching users/orders.
 * Export on the source, copy JSON privately, import on the destination.
 */
class SyncNollyflixVideos extends Command
{
    protected $signature = 'nollyflix:sync-videos
        {file? : JSON export to read in --import mode}
        {--export : Export video metadata and catalogue associations from this installation}
        {--import : Import video metadata from a JSON export}
        {--apply : Actually write imported records (otherwise perform a dry run)}
        {--active-only : Export only active/live videos}';

    protected $description = 'Safely export or synchronize NollyFlix video catalogue metadata between installations';

    public function handle(): int
    {
        if ((bool) $this->option('export') === (bool) $this->option('import')) {
            $this->error('Choose exactly one of --export or --import.');
            return 1;
        }

        return $this->option('export') ? $this->exportData() : $this->importData();
    }

    private function exportData(): int
    {
        $columns = $this->safeColumns();
        $query = Video::query()->with(['categories', 'genres', 'sections']);
        if ($this->option('active-only')) {
            $query->active();
        }

        $rows = [];
        foreach ($query->orderBy('id')->cursor() as $video) {
            // Eager-load associations per record (cursor does not eager-load in Laravel).
            $video->load(['categories', 'genres', 'sections']);
            $row = [];
            foreach ($columns as $column) {
                $row[$column] = $video->getRawOriginal($column);
            }
            $rows[] = [
                'attributes' => $row,
                'categories' => $video->categories->pluck('slug')->filter()->values()->all(),
                'genres' => $video->genres->pluck('slug')->filter()->values()->all(),
                'sections' => $video->sections->pluck('slug')->filter()->values()->all(),
            ];
        }

        $directory = storage_path('app/nollyflix-sync');
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            $this->error('Cannot create export directory.');
            return 1;
        }
        $path = $directory.'/videos-'.date('Ymd-His').'.json';
        $json = json_encode([
            'format' => 'nollyflix-videos-v1',
            'exported_at' => now()->toIso8601String(),
            'videos' => $rows,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        if (!$json || file_put_contents($path, $json, LOCK_EX) === false) {
            $this->error('Could not write export JSON.');
            return 1;
        }
        @chmod($path, 0640);
        $this->info('Exported '.count($rows).' videos to '.$path);
        $this->warn('The export may contain private playback URLs. Transfer it securely; never put it in public/.');
        return 0;
    }

    private function importData(): int
    {
        $path = $this->argument('file');
        if (!$path || !is_file($path) || !is_readable($path)) {
            $this->error('Provide a readable JSON export file path.');
            return 1;
        }
        $data = json_decode(file_get_contents($path), true);
        if (!is_array($data) || ($data['format'] ?? null) !== 'nollyflix-videos-v1' || !isset($data['videos']) || !is_array($data['videos'])) {
            $this->error('Invalid or unsupported NollyFlix video export.');
            return 1;
        }
        $apply = (bool) $this->option('apply');
        $columns = array_flip($this->safeColumns());
        $created = 0;
        $updated = 0;
        $skipped = 0;
        foreach ($data['videos'] as $item) {
            $attrs = array_intersect_key((array) ($item['attributes'] ?? []), $columns);
            $slug = trim((string) ($attrs['slug'] ?? ''));
            if ($slug === '' || trim((string) ($attrs['title'] ?? '')) === '') {
                $skipped++;
                continue;
            }
            $existing = Video::where('slug', $slug)->first();
            if (!$existing) {
                $existing = Video::where('title', $attrs['title'])->first();
            }
            if ($existing) {
                $updated++;
            } else {
                $created++;
            }
            if (!$apply) {
                continue;
            }

            DB::transaction(function () use ($existing, $attrs, $item) {
                $video = $existing ?: new Video();
                foreach ($attrs as $column => $value) {
                    $video->setAttribute($column, $value);
                }
                $video->save();
                $this->syncExistingSlugs($video, 'categories', Category::class, $item);
                $this->syncExistingSlugs($video, 'genres', Genre::class, $item);
                $this->syncExistingSlugs($video, 'sections', Section::class, $item);
            });
        }
        $this->line(($apply ? 'Imported' : 'DRY RUN (no changes)').": {$created} new, {$updated} existing, {$skipped} skipped.");
        $this->warn('Only video metadata is synced. Category/genre/section links are matched to existing destination slugs. Users, payments and source media files are not modified.');
        return 0;
    }

    private function syncExistingSlugs(Video $video, string $relation, string $model, array $item): void
    {
        if (!isset($item[$relation]) || !is_array($item[$relation])) {
            return;
        }
        $slugs = array_values(array_filter($item[$relation], 'is_string'));
        if (!$slugs) {
            return; // Never wipe mappings due to missing source records.
        }
        $ids = $model::whereIn('slug', $slugs)->pluck('id')->all();
        if ($ids) {
            $video->{$relation}()->syncWithoutDetaching($ids);
        }
    }

    private function safeColumns(): array
    {
        // Different deployments can have different migrations; write only shared video fields.
        return array_values(array_diff(Schema::getColumnListing('videos'), [
            'id', 'created_at', 'updated_at', 'deleted_at', 'user_id', 'content_owner_id',
        ]));
    }
}
