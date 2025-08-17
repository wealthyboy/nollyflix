<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Section;
use App\Video;
use App\Category;
use App\Cast;
use App\Filmer;
use App\Genre;

class ImportVideos extends Command
{
    protected $signature = 'import:videos {url}';
    protected $description = 'Import videos and sections from API';

    public function handle()
    {
        $url = $this->argument('url');
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error("Failed to fetch data from API");
            return;
        }

        $data = $response->json('data');
       

        foreach ($data as $sectionData) {

            foreach ($sectionData as $section) {
             if (  data_get($section, 'id')) {
            $s = Section::updateOrCreate(
                ['id' => $section['id']],
                ['name' => $section['name'], 'slug' => $section['slug']]
            );



            foreach ($section['videos'] as $videoData) {
                $video = Video::updateOrCreate(
                    ['id' => $videoData['id']], // assuming video id exists in API
                    [
                        'title' => $videoData['title'],
                        'slug' => $videoData['slug'] ?? str_slug($videoData['title']),
                        'preview_link' => $videoData['preview_link'] ?? null,
                        'duration' => $videoData['duration'] ?? null,
                        'poster' => $videoData['poster'] ?? null,
                        'tn_poster' => $videoData['tn_poster'] ?? null,
                        'buy_price' => $videoData['buy_price'] ?? 0,
                        'rent_price' => $videoData['rent_price'] ?? 0,
                        'film_rating' => $videoData['film_rating'] ?? null,
                        'description' => $videoData['description'] ?? null,
                        'resolution' => $videoData['resolution'] ?? null,
                        'release_date' => $videoData['release_date'] ?? null,
                        'link' => $videoData['link'] ?? null,
                        'iframe' => $videoData['iframe'] ?? null,
                        'blocked_continents' => $videoData['blocked_continents'] ?? null,
                        'featured' => $videoData['featured'] ?? 0,
                        'access_type' => $videoData['access_type'] ?? 'free',
                    ]
                );

                // Sync relations if they exist in JSON
                if (!empty($videoData['cast_id'])) {
                    $video->casts()->sync($videoData['cast_id']);
                }

                if (!empty($videoData['genre_id'])) {
                    $video->genres()->sync($videoData['genre_id']);
                }

                if (!empty($videoData['filmer_id'])) {
                    $video->filmers()->sync($videoData['filmer_id']);
                }

                if (!empty($videoData['category_id'])) {
                    $video->categories()->sync($videoData['category_id']);
                }

                $video->sections()->syncWithoutDetaching([$section['id']]);
            }

            $this->info("Imported section: {$section['name']}");
        }

        $this->info("✅ Import completed successfully!");
    }
}
}
}
