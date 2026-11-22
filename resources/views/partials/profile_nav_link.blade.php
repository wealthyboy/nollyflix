<ul id="dashboard-sub-menu">
    @if (in_array($user->type, ['casts', 'filmakers'], true))
        <li class="{{ $current == 'dashboard' ? 'current' : '' }}"><a href="{{ route('actors.dashboard') }}">My Movies & Stats</a></li>
    @endif
    <li class="{{  $current  == 'profile' ? 'current' : '' }}"><a href="{{ route('profile.index') }}">Account Settings</a></li>
    @if (!$user->isSubscriber()  && !$user->isAdmin())
        <li class="{{ $current == 'videos' ? 'current' : '' }}" ><a href="{{ route('profiles.videos') }}">Videos({{ $user->profile_videos->count() }})</a></li>
    @endif
    <li class="{{ $current == 'watchlists' ? 'current' : '' }}"><a href="{{ route('profiles.watchlists') }}">Watchlist({{ $user->movies->count() }})</a></li>
    <li><a href="{{ route('account-deletion.show') }}">Delete account</a></li>
</ul>
