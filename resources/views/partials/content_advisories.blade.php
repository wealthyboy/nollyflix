@if($video->contentAdvisories->isNotEmpty())
<section class="content-sidebar-short-description mt-3" aria-label="Content advisories">
    <strong class="d-block mb-2">Content advisories</strong>
    <ul class="list-unstyled d-flex flex-wrap mb-0" style="gap:8px;">
        @foreach($video->contentAdvisories as $advisory)
            <li style="border:1px solid #555; border-radius:4px; padding:4px 10px; line-height:1.5;">{{ $advisory->name }}</li>
        @endforeach
    </ul>
</section>
@endif
