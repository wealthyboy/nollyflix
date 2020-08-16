<!-- <div id="content-sidebar-info">
    <div id="avatar-sidebar-large-profile" style="background-image:url('images/demo/profile-image.jpg')"></div>
    <div id="profile-sidebar-gradient"></div>
    <a href="#" class="edit-profile-sidebar">Edit</a>
</div> -->
<!-- close .content-sidebar-info -->

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

@if (!$user->isSubscriber())
    <!-- close .content-sidebar-section -->
    <div class="content-sidebar-section">
        <h3 class="content-sidebar-sub-header">Biography</h3>
        <div class="content-sidebar-biography-text">
        {!!  $user->description !!} 
        </div>
    </div>
    <!-- close .content-sidebar-section -->
@endif