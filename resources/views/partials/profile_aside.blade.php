{{-- Show a local placeholder whenever a contributor has no uploaded profile photo.
     The Vue component draws uploaded photos over this background. --}}
<style>
#content-sidebar-info {
    background: #190c18 url("{{ asset('images/default-contributor-avatar.jpg') }}") center center / cover no-repeat;
}
#content-sidebar-info #avatar-sidebar-large-profile {
    background-color: transparent;
}
</style>
<div id="content-sidebar-info">
    <profile-picture />
</div>

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