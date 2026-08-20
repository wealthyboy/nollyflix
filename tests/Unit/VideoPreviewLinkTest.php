<?php

namespace Tests\Unit;

use App\Video;
use Tests\TestCase;

class VideoPreviewLinkTest extends TestCase
{
    public function test_signed_vimeo_preview_url_is_preserved_exactly()
    {
        $url = 'https://player.vimeo.com/progressive_redirect/playback/1234567890/rendition/1080p/file.mp4%20%281080p%29.mp4?loc=external&signature=test-signature';

        $this->assertSame($url, Video::normalizePreviewLink($url));
    }

    public function test_show_page_uses_an_enhanced_native_player_and_the_raw_preview_url()
    {
        $template = file_get_contents(resource_path('views/partials/video_show.blade.php'));

        $this->assertStringContainsString('controls playsinline webkit-playsinline preload="metadata"', $template);
        $this->assertStringContainsString('data-nollyflix-player', $template);
        $this->assertStringContainsString('data-player-action="fullscreen"', $template);
        $this->assertStringContainsString('data-player-error', $template);
        $this->assertStringContainsString('{{ $video->preview_link }}', $template);
        $this->assertStringNotContainsString('playablePreviewLink()', $template);
    }

    public function test_trailer_script_does_not_rewrite_the_video_source()
    {
        $script = file_get_contents(public_path('js/trailer.js'));
        $styles = file_get_contents(public_path('css/trailer.css'));

        $this->assertStringNotContainsString('video.src =', $script);
        $this->assertStringNotContainsString('document.body.style.cursor', $script);
        $this->assertStringContainsString("element.removeAttribute('hidden')", $script);
        $this->assertStringContainsString("video.controls = false", $script);
        $this->assertStringContainsString("video.webkitEnterFullscreen", $script);
        $this->assertStringContainsString(".nollyflix-player.is-playing [data-play-button] [data-icon='pause']", $styles);
    }
}
