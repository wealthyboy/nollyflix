<?php

use App\ContentAdvisory;
use Illuminate\Database\Seeder;

class ContentAdvisorySeeder extends Seeder
{
    public function run()
    {
        $labels = [
            'violence' => 'Violence',
            'sexual-content' => 'Sexual content',
            'nudity' => 'Nudity',
            'strong-language' => 'Strong language',
            'alcohol' => 'Alcohol',
            'smoking' => 'Smoking',
            'drug-use' => 'Drug use',
            'disturbing-scenes' => 'Disturbing scenes',
        ];

        foreach (array_keys($labels) as $index => $slug) {
            // Re-running the seeder must not overwrite an administrator's edits.
            ContentAdvisory::firstOrCreate(['slug' => $slug], [
                'name' => $labels[$slug],
                'sort_order' => ($index + 1) * 10,
            ]);
        }
    }
}
