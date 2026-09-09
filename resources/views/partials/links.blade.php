@php
    $browseVideoUrl = isset($profile_user)
        ? route('browse.user.show', ['video' => $video, 'user' => $profile_user])
        : route('browse.show', ['video' => $video]);
@endphp


<a href="{{ $video->playablePreviewLink() }}" class="btn anchor-btn rounded-0" data-fancybox id=""><i class="far fa-play-circle"></i>Play Trailer </a>
@if ($video->access_type == 'coming_soon')
@elseif ($video->access_type == 'is_free')
    <a href="/watch/{{ $video->id }}?watch=free" class="btn anchor-btn rounded-0"    id=""><i class="fas fa-play-circle"></i>Watch </a>
@elseif($video->access_type == 'is_for_rent_and_buy')
    <a href="{{ $browseVideoUrl }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Buy {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
    <a href="{{ $browseVideoUrl }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Rent {{ $video->currency }}{{ number_format($video->converted_rent_price) }} </a>
@elseif($video->access_type == 'is_only_for_rent')
    <a href="{{ $browseVideoUrl }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Rent {{ $video->currency }}{{ number_format($video->converted_rent_price) }} </a>
@elseif($video->access_type == 'is_only_for_buy')
    <a href="{{ $browseVideoUrl }}" class="btn anchor-btn rounded-0"  id=""><i class="fas fa-shopping-cart"></i>Buy {{ $video->currency }}{{ number_format($video->converted_buy_price) }} </a>
@else
@endif
