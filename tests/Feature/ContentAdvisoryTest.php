<?php

namespace Tests\Feature;

use App\ContentAdvisory;
use App\Genre;
use App\Permission;
use App\Section;
use App\User;
use App\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentAdvisoryTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $genre;
    private $section;
    private $cast;
    private $filmer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->withHeaders(['CF-IPCountry' => 'NG']);
        $this->admin = $this->user('advisory-admin', 'admin');
        $permission = Permission::create(['name' => 'Advisory administrator', 'code' => '1,2,3']);
        $this->admin->users_permission()->create(['permission_id' => $permission->id]);
        $this->cast = $this->user('advisory-cast', 'casts');
        $this->filmer = $this->user('advisory-filmer', 'filmakers');
        $this->genre = Genre::create(['name' => 'Drama', 'slug' => 'drama']);
        $this->section = new Section;
        $this->section->forceFill(['name' => 'Movies', 'slug' => 'movies'])->save();
    }

    public function test_default_seeder_is_repeatable_and_never_classifies_existing_movies()
    {
        $video = $this->movie();
        $seeder = $this->seedDefaults();
        $violence = ContentAdvisory::where('slug', 'violence')->firstOrFail();
        $violence->update(['name' => 'Strong violence', 'sort_order' => 99]);
        $seeder->run();

        $this->assertDatabaseCount('content_advisories', 8);
        $this->assertDatabaseCount('content_advisory_video', 0);
        $this->assertSame('Strong violence', $violence->fresh()->name);
        $this->assertSame(99, $violence->fresh()->sort_order);
        $this->assertSame('PG13', $video->fresh()->film_rating);
    }

    public function test_upload_saves_selected_advisories_and_keeps_the_age_rating()
    {
        $this->seedDefaults();
        $ids = ContentAdvisory::ordered()->take(2)->pluck('id')->all();
        $this->actingAs($this->admin)->post(route('videos.store'), $this->payload('New Movie') + [
            'content_advisory_ids' => $ids,
        ])->assertRedirect(route('videos.index'))->assertSessionHasNoErrors();

        $video = Video::where('title', 'New Movie')->firstOrFail();
        $this->assertSame($ids, $video->contentAdvisories->pluck('id')->all());
        $this->assertSame('PG13', $video->film_rating);
    }

    public function test_edit_replaces_selections_and_unchecking_everything_clears_them()
    {
        $this->seedDefaults();
        $ids = ContentAdvisory::ordered()->take(2)->pluck('id')->all();
        $video = $this->movie();
        $video->contentAdvisories()->sync([$ids[0]]);
        $this->actingAs($this->admin)->patch(route('videos.update', $video->id), $this->payload($video->title) + [
            'content_advisory_ids' => [$ids[1]],
        ])->assertRedirect(route('videos.index'))->assertSessionHasNoErrors();
        $this->assertSame([$ids[1]], $video->fresh()->contentAdvisories->pluck('id')->all());

        $this->patch(route('videos.update', $video->id), $this->payload($video->title))
            ->assertRedirect(route('videos.index'))->assertSessionHasNoErrors();
        $this->assertCount(0, $video->fresh()->contentAdvisories);
    }

    public function test_older_update_clients_do_not_erase_advisories_they_did_not_submit()
    {
        $this->seedDefaults();
        $video = $this->movie();
        $id = ContentAdvisory::first()->id;
        $video->contentAdvisories()->sync([$id]);
        $payload = $this->payload($video->title);
        unset($payload['content_advisories_present']);
        $this->actingAs($this->admin)->patch(route('videos.update', $video->id), $payload)
            ->assertRedirect(route('videos.index'))->assertSessionHasNoErrors();
        $this->assertSame([$id], $video->fresh()->contentAdvisories->pluck('id')->all());
    }

    public function test_invalid_or_duplicate_advisories_are_rejected_before_video_changes()
    {
        $this->seedDefaults();
        $video = $this->movie();
        $id = ContentAdvisory::first()->id;
        $video->contentAdvisories()->sync([$id]);
        $this->actingAs($this->admin)->patch(route('videos.update', $video->id), $this->payload('Changed') + [
            'content_advisory_ids' => [999999],
        ])->assertSessionHasErrors('content_advisory_ids.0');
        $this->post(route('videos.store'), $this->payload('Invalid new movie') + [
            'content_advisory_ids' => [$id, $id],
        ])->assertSessionHasErrors('content_advisory_ids.0');
        $this->assertSame($video->title, $video->fresh()->title);
        $this->assertSame([$id], $video->fresh()->contentAdvisories->pluck('id')->all());
        $this->assertDatabaseMissing('videos', ['title' => 'Invalid new movie']);
    }

    public function test_advisory_management_validates_labels_and_preserves_stable_seed_slugs()
    {
        $this->seedDefaults();
        $advisory = ContentAdvisory::where('slug', 'violence')->firstOrFail();
        $this->actingAs($this->admin)->patch(route('content-advisories.update', $advisory), [
            'name' => 'Strong violence', 'sort_order' => 5,
        ])->assertRedirect(route('content-advisories.index'))->assertSessionHasNoErrors();
        $this->assertSame('violence', $advisory->fresh()->slug);
        $this->assertSame('Strong violence', $advisory->fresh()->name);
        $this->post(route('content-advisories.store'), ['name' => 'Self-harm', 'sort_order' => 90])
            ->assertRedirect(route('content-advisories.index'));
        $this->post(route('content-advisories.store'), ['name' => 'Self-harm'])
            ->assertSessionHasErrors('name');
        $this->post(route('content-advisories.store'), ['name' => '!!!'])->assertSessionHasErrors('slug');
        $this->post(route('content-advisories.store'), ['name' => ['Invalid']])->assertSessionHasErrors('name');
        $this->assertDatabaseCount('content_advisories', 9);
    }

    public function test_guests_and_customers_cannot_manage_advisories_or_change_movies()
    {
        $video = $this->movie();
        $this->getJson(route('content-advisories.index'))->assertNotFound();
        $this->postJson(route('content-advisories.store'), ['name' => 'Violence'])->assertNotFound();
        $this->actingAs($this->user('advisory-customer', 'subscriber'))
            ->patchJson(route('videos.update', $video->id), $this->payload('Unauthorized'))->assertNotFound();

        $permission = Permission::create(['name' => 'Read-only', 'code' => '1']);
        $this->admin->users_permission()->update(['permission_id' => $permission->id]);
        $this->actingAs($this->admin->fresh())->postJson(route('content-advisories.store'), ['name' => 'Violence'])
            ->assertForbidden();
        $this->patchJson(route('videos.update', $video->id), $this->payload('Unauthorized'))->assertForbidden();
        $this->assertSame($video->title, $video->fresh()->title);
    }

    public function test_public_details_api_returns_only_selected_advisories_in_display_order()
    {
        $this->seedDefaults();
        $video = $this->movie();
        $ids = ContentAdvisory::ordered()->take(2)->pluck('id')->all();
        $video->contentAdvisories()->sync(array_reverse($ids));
        $response = $this->getJson('/api/video/'.$video->id)->assertOk()
            ->assertJsonPath('data.rating', 'PG13')
            ->assertJsonCount(2, 'data.content_advisories')
            ->assertJsonPath('data.content_advisories.0.slug', 'violence')
            ->assertJsonPath('data.content_advisories.1.slug', 'sexual-content');
        $this->assertArrayNotHasKey('stream_url', $response->json('data'));

        $video->contentAdvisories()->detach();
        $this->getJson('/api/video/'.$video->id)->assertOk()->assertJsonPath('data.content_advisories', []);
    }

    public function test_frontend_has_escaped_selected_labels_and_hides_empty_advisories()
    {
        $video = $this->movie();
        $advisory = ContentAdvisory::create(['name' => '<script>alert(1)</script>', 'slug' => 'test', 'sort_order' => 1]);
        $video->contentAdvisories()->sync([$advisory->id]);
        $html = view('partials.content_advisories', ['video' => $video->fresh()])->render();
        $this->assertStringContainsString('Content advisories', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $video->contentAdvisories()->detach();
        $this->assertSame('', trim(view('partials.content_advisories', ['video' => $video->fresh()])->render()));
    }

    public function test_public_show_page_displays_selected_advisories_under_the_metadata()
    {
        factory(\App\SystemSetting::class)->create(['address' => 'Test address', 'site_email' => 'support@example.test']);
        $this->seedDefaults();
        $video = $this->movie();
        $video->contentAdvisories()->sync([ContentAdvisory::where('slug', 'violence')->firstOrFail()->id]);
        $_SERVER['REQUEST_URI'] = '/browse/'.$video->slug;
        $this->get(route('browse.show', $video))->assertOk()
            ->assertSee('Content advisories')->assertSee('Violence')->assertDontSee('Strong language');
    }

    public function test_edit_checkboxes_restore_saved_selections_and_old_input_including_clear_all()
    {
        $this->seedDefaults();
        $video = $this->movie();
        $advisory = ContentAdvisory::ordered()->first();
        $video->contentAdvisories()->sync([$advisory->id]);
        $data = ['video' => $video->fresh(), 'contentAdvisories' => ContentAdvisory::ordered()->get()];
        $this->assertSame(1, substr_count(view('admin.videos.content_advisories', $data)->render(), 'checked'));
        session()->flashInput(['content_advisories_present' => '1']);
        request()->setLaravelSession(app('session.store'));
        $this->assertSame(0, substr_count(view('admin.videos.content_advisories', $data)->render(), 'checked'));
    }

    public function test_admin_pages_render_the_menu_and_saved_checkbox_selection()
    {
        $this->seedDefaults();
        $video = $this->movie();
        $advisory = ContentAdvisory::ordered()->first();
        $video->contentAdvisories()->sync([$advisory->id]);
        $_SERVER['REQUEST_URI'] = '/admin/content-advisories';
        $this->actingAs($this->admin)->get(route('content-advisories.index'))
            ->assertOk()->assertSee('Content Advisories')->assertSee('Strong language');
        $this->get(route('content-advisories.edit', $advisory))->assertOk()->assertSee('Edit content advisory');
        $_SERVER['REQUEST_URI'] = '/admin/videos/create';
        $this->get(route('videos.create'))->assertOk()->assertSee('content_advisory_ids[]', false);
        $_SERVER['REQUEST_URI'] = '/admin/videos/'.$video->id.'/edit';
        $this->get(route('videos.edit', $video->id))->assertOk()->assertSee('content_advisory_ids[]', false);
    }

    private function seedDefaults()
    {
        // Also works before Composer's class map is regenerated after deployment.
        require_once database_path('seeds/ContentAdvisorySeeder.php');
        $seeder = new \ContentAdvisorySeeder;
        $seeder->run();
        return $seeder;
    }

    private function user($name, $type)
    {
        return User::create(['name' => $name, 'last_name' => 'Test', 'email' => $name.'@example.test',
            'password' => bcrypt('test-password'), 'type' => $type]);
    }

    private function movie()
    {
        $video = new Video;
        $video->forceFill(['title' => 'Advisory Test', 'slug' => 'advisory-test', 'film_rating' => 'PG13',
            'is_active' => true, 'is_free' => true, 'content_type' => 'movie'])->save();
        return $video;
    }

    private function payload($title)
    {
        return ['title' => $title, 'content_type' => 'movie', 'film_rating' => 'PG13',
            'description' => 'Test movie description.', 'link' => 'https://media.example.test/movie.m3u8',
            'poster' => '/images/test-poster.jpg', 'tn_poster' => '/images/test-thumb.jpg',
            'buy_price' => 1000, 'rent_price' => 500, 'access_type' => 'is_free',
            'cast_id' => [$this->cast->id], 'filmer_id' => [$this->filmer->id],
            'genre_id' => [$this->genre->id], 'section_id' => [$this->section->id],
            'content_advisories_present' => '1'];
    }
}
