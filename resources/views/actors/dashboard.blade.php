@extends('layouts.profile')

@section('content')
<div id="content-pro">
    <div class="container custom-gutters-pro">
        <div id="vayvo-progression-author-sidebar">
            @include('partials.profile_aside', ['user' => $user])
        </div>
        <div id="vayvo-progression-author-content-container">
            @include('partials.profile_nav_link', ['user' => $user, 'current' => 'dashboard'])
            <h1>{{ $user->type === 'casts' ? 'Actor Dashboard' : 'Filmmaker Dashboard' }}</h1>
            <p>Welcome, {{ $user->fullname() }}. See how your linked movies are performing. Only movies linked to your profile appear here.</p>
            @include('errors.errors')
            <form method="get" action="{{ route('actors.dashboard') }}" class="row mb-4">
                <div class="col-md-4"><label for="stats-from">From</label><input id="stats-from" type="date" name="from" value="{{ $from }}" class="form-control"></div>
                <div class="col-md-4"><label for="stats-to">To</label><input id="stats-to" type="date" name="to" value="{{ $to }}" class="form-control"></div>
                <div class="col-md-4 pt-4"><button type="submit" class="btn btn-primary">Apply dates</button> <a href="{{ route('actors.dashboard') }}">All time</a></div>
            </form>
            <div class="row mb-4">
                <div class="col-md-4"><h2>{{ number_format($films->count()) }}</h2><p>Linked films</p></div>
                <div class="col-md-4"><h2>{{ number_format($films->sum('clicks')) }}</h2><p>Film page clicks</p></div>
                <div class="col-md-4"><h2>{{ number_format($films->sum('watches')) }}</h2><p>Watches started</p></div>
            </div>
            <p>These numbers represent activity recorded by Nollyflix, not third-party platforms. Clicks count film detail page visits, including repeat visits. Watches count playback starts once per player page session; pausing and resuming does not add another watch. Admin previews are excluded. These figures cover the website from this update onward.</p>
            @if($trackingStarted)<p>First recorded activity: {{ \Carbon\Carbon::parse($trackingStarted)->format('j M Y') }}.</p>@endif
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th scope="col">Film</th><th scope="col">Clicks</th><th scope="col">Watches started</th></tr></thead>
                    <tbody>
                    @forelse($films as $film)
                        <tr><td><a href="{{ route('browse.show', $film->slug) }}">{{ $film->title }}</a></td><td>{{ number_format($film->clicks) }}</td><td>{{ number_format($film->watches) }}</td></tr>
                    @empty
                        <tr><td colspan="3">No films linked yet. Ask the administrator to link movies to your contributor profile.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
