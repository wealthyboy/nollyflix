{{-- Native uploader: works without recompiling the Vue bundle. --}}
<style>
#nolly-profile-photo-wrap { position: relative; width: 100%; background: #190c18; }
#nolly-profile-photo-wrap img { width: 100%; height: 275px; display: block; object-fit: cover; object-position: center; background: #190c18; }
#nolly-profile-photo-wrap .nolly-profile-edit { display: flex; width: 100%; min-height: 45px; align-items: center; justify-content: center; gap: 7px; border: 0; border-radius: 0; background: #393939; color: #fff; font-size: 14px; cursor: pointer; }
#nolly-profile-photo-wrap .nolly-profile-edit:hover, #nolly-profile-photo-wrap .nolly-profile-edit:focus { background: #505050; }
#nolly-profile-photo-wrap .nolly-profile-edit:disabled { opacity: .75; cursor: progress; }
#nolly-profile-photo-wrap .nolly-profile-notice { display: none; margin: 0; padding: 9px 12px; font-size: 12px; line-height: 1.5; color: white; background: #333; }
#nolly-profile-photo-wrap .nolly-profile-notice[data-kind="error"] { background: #9e1730; }
#nolly-profile-photo-wrap .nolly-profile-notice[data-kind="success"] { background: #176b40; }
</style>
<div id="content-sidebar-info">
    <div id="nolly-profile-photo-wrap">
        <img id="nolly-profile-photo" src="{{ $user->m_path() }}" alt="{{ $user->fullname() }} profile photo" onerror="this.onerror=null;this.src='{{ asset('images/default-contributor-avatar.jpg') }}';">
        <button id="nolly-profile-edit" type="button" class="nolly-profile-edit"><i class="fas fa-edit" aria-hidden="true"></i><span>Edit</span></button>
        <input id="nolly-profile-file" type="file" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" aria-label="Choose profile image">
        <p id="nolly-profile-notice" class="nolly-profile-notice" aria-live="polite"></p>
    </div>
</div>
<script>
(function () {
    function initNollyProfilePhoto() {
        var wrapper = document.getElementById('nolly-profile-photo-wrap');
        if (!wrapper || wrapper.dataset.ready === '1') return;
        wrapper.dataset.ready = '1';
        var button = document.getElementById('nolly-profile-edit');
        var input = document.getElementById('nolly-profile-file');
        var photo = document.getElementById('nolly-profile-photo');
        var notice = document.getElementById('nolly-profile-notice');
        var fallback = @json(asset('images/default-contributor-avatar.jpg'));
        var csrf = @json(csrf_token());
        function status(message, kind) {
            notice.textContent = message || '';
            notice.dataset.kind = kind || '';
            notice.style.display = message ? 'block' : 'none';
        }
        button.addEventListener('click', function () { if (!button.disabled) input.click(); });
        input.addEventListener('change', async function () {
            var file = input.files && input.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type)) {
                status('Please select a JPG, PNG, GIF or WebP image.', 'error'); input.value = ''; return;
            }
            if (file.size > 8 * 1024 * 1024) {
                status('Image must be 8 MB or smaller.', 'error'); input.value = ''; return;
            }
            var previous = photo.src;
            var preview = URL.createObjectURL(file);
            photo.src = preview;
            button.disabled = true;
            button.querySelector('span').textContent = 'Uploading...';
            status('Uploading your profile photo...', '');
            var data = new FormData();
            data.append('file', file);
            try {
                var response = await fetch(@json(url('/profile/picture')), {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: data
                });
                var result = await response.json().catch(function () { return {}; });
                if (!response.ok || !result.url) {
                    var details = result.message || (response.status === 419 ? 'Session expired. Refresh the page and sign in again.' : 'Upload failed. Please try again.');
                    throw new Error(details);
                }
                photo.src = result.url + (result.url.indexOf('?') === -1 ? '?' : '&') + 'v=' + Date.now();
                // The header uses the same account photo; update it after upload on the next page load.
                status('Profile image updated successfully.', 'success');
            } catch (error) {
                photo.src = previous || fallback;
                status(error.message || 'Upload failed. Please try again.', 'error');
            } finally {
                URL.revokeObjectURL(preview);
                button.disabled = false;
                button.querySelector('span').textContent = 'Edit';
                input.value = '';
            }
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initNollyProfilePhoto);
    else initNollyProfilePhoto();
})();
</script>

<div id="vayvo-profile-sidebar-name">
    <h5>{{ $user->fullname() }}</h5> 			
</div><!-- close #vayvo-profile-sidebar-name -->

<div class="content-sidebar-section">
    <h3 class="content-sidebar-sub-header">User Stats</h3>
    <ul id="profile-watched-stats">
        @if (!$user->isSubscriber())
            <li>
                <span>{{ $user->profile_videos->count() }}</span>
                Videos
            </li>
        @endif
            <li>
                <span>{{ $user->movies->count() }}</span>
                Watchlist
            </li>
    </ul>
</div>

@if (!$user->isSubscriber()  && !$user->isAdmin())
    <!-- close .content-sidebar-section -->
    <div class="content-sidebar-section">
        <h3 class="content-sidebar-sub-header">Biography</h3>
        <div class="content-sidebar-biography-text">
        {!!  $user->description !!} 
        </div>
    </div>
    <!-- close .content-sidebar-section -->
@endif