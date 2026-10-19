<?php

namespace Tests\Feature;

use App\Jobs\SendPushCampaign;
use App\Permission;
use App\PushCampaign;
use App\User;
use App\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class MoviePushNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $administrator;
    protected $movies;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        Bus::fake();
        $this->administrator = User::create([
            'name' => 'Admin', 'last_name' => 'Test', 'email' => 'push-admin@example.test',
            'password' => bcrypt('test-password'), 'type' => 'admin',
        ]);
        $permission = Permission::create(['name' => 'Super User', 'code' => '1,2,3']);
        $this->administrator->users_permission()->create(['permission_id' => $permission->id]);
        $this->movies = collect(['First Movie', 'Second Movie'])->map(function ($title, $index) {
            $video = new Video;
            $video->forceFill(['title' => $title, 'slug' => 'push-movie-'.$index,
                'description' => '<p>The movie description.</p>', 'is_active' => true])->save();
            return $video;
        });
    }

    public function test_each_movie_gets_its_own_custom_message_and_exact_deep_link()
    {
        $first = $this->movies[0];
        $second = $this->movies[1];
        $this->actingAs($this->administrator)->post(route('videos.push-notification'), [
            'selected' => [$first->id, $second->id],
            'messages' => [
                $first->id => ['title' => 'Tonight\'s premiere', 'body' => 'A special message for movie one.'],
                $second->id => ['title' => 'Your next watch', 'body' => 'A different message for movie two.'],
            ],
        ])->assertRedirect(route('videos.index'))->assertSessionHas('success');

        $campaigns = PushCampaign::orderBy('id')->get();
        $this->assertCount(2, $campaigns);
        foreach ($campaigns as $index => $campaign) {
            $video = $this->movies[$index];
            $this->assertSame('nollyflix://video/'.$video->id, $campaign->data['url']);
            $this->assertSame((string) $video->id, $campaign->data['video_id']);
            $this->assertSame([(int) $video->id], $campaign->videos->pluck('id')->map(function ($id) { return (int) $id; })->all());
            $this->assertSame('queued', $campaign->status);
        }
        $this->assertSame('Tonight\'s premiere', $campaigns[0]->title);
        $this->assertSame('A special message for movie one.', $campaigns[0]->body);
        $this->assertSame('Your next watch', $campaigns[1]->title);
        $this->assertSame('A different message for movie two.', $campaigns[1]->body);
        Bus::assertDispatched(SendPushCampaign::class, 2);
    }

    public function test_legacy_requests_still_generate_one_movie_linked_notification_per_selection()
    {
        $this->actingAs($this->administrator)->post(route('videos.push-notification'), [
            'selected' => $this->movies->pluck('id')->all(),
        ])->assertRedirect(route('videos.index'));
        $this->assertDatabaseCount('push_campaigns', 2);
        $campaign = PushCampaign::first();
        $this->assertSame('New on Nollyflix: First Movie', $campaign->title);
        $this->assertSame('The movie description.', $campaign->body);
    }

    public function test_invalid_messages_or_duplicate_selections_queue_nothing()
    {
        $id = $this->movies[0]->id;
        $this->actingAs($this->administrator)->post(route('videos.push-notification'), [
            'selected' => [$id], 'messages' => [$id => ['title' => str_repeat('x', 151)]],
        ])->assertSessionHasErrors('messages.'.$id.'.title');
        $this->post(route('videos.push-notification'), ['selected' => [$id, $id]])->assertSessionHasErrors('selected.0');
        $this->assertDatabaseCount('push_campaigns', 0);
        Bus::assertNotDispatched(SendPushCampaign::class);
    }

    public function test_guest_cannot_queue_a_broadcast()
    {
        $this->postJson(route('videos.push-notification'), ['selected' => $this->movies->pluck('id')->all()])->assertNotFound();
        $this->assertDatabaseCount('push_campaigns', 0);
        Bus::assertNotDispatched(SendPushCampaign::class);
    }
}
