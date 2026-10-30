@php
    $selectedAdvisories = old('content_advisories_present') !== null
        ? old('content_advisory_ids', [])
        : (isset($video) ? $video->contentAdvisories->pluck('id')->all() : []);
@endphp
<div class="row">
    <div class="col-md-12">
        <fieldset class="video-content-advisories" style="margin:12px 0 24px; padding:16px; border:1px solid #ddd; border-radius:6px;">
            <legend style="width:auto; border:0; margin-bottom:8px; padding:0 6px; font-size:16px;">Content Advisories</legend>
            <p class="text-muted">Tick only the content present in this movie or series. These labels will appear on its details page, separately from the age rating.</p>
            <input type="hidden" name="content_advisories_present" value="1">
            <div class="row">
                @forelse($contentAdvisories as $advisory)
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label style="color:#333; font-weight:400;">
                                <input type="checkbox" name="content_advisory_ids[]" value="{{ $advisory->id }}"
                                    {{ in_array($advisory->id, (array) $selectedAdvisories) ? 'checked' : '' }}>
                                {{ $advisory->name }}
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12"><p>No advisory labels have been added yet.</p></div>
                @endforelse
            </div>
            <a href="{{ route('content-advisories.index') }}" target="_blank" rel="noopener">Manage advisory labels</a>
        </fieldset>
    </div>
</div>
