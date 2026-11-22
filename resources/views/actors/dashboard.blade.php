@extends('layouts.profile')

@section('page-css')
<style>
/* Contributor dashboard only: avoid global profile float/footer collisions. */
#content-pro.nolly-contributor-dashboard { min-height: 65vh; padding: 38px 0 74px; }
.nolly-contributor-dashboard .nolly-contributor-layout { display: flex; gap: 36px; align-items: flex-start; }
.nolly-contributor-dashboard #vayvo-progression-author-sidebar,
.nolly-contributor-dashboard #vayvo-progression-author-content-container { float: none; padding-top: 0; min-width: 0; }
.nolly-contributor-dashboard #vayvo-progression-author-sidebar { width: 260px; flex: 0 0 260px; }
.nolly-contributor-dashboard #vayvo-progression-author-content-container { width: auto; flex: 1 1 auto; }
.nolly-contributor-dashboard #dashboard-sub-menu { display:flex; flex-wrap:wrap; align-items:stretch; width:100%; padding:0; margin:0 0 30px; overflow:visible; border-bottom:1px solid rgba(255,255,255,.15); }
.nolly-contributor-dashboard #dashboard-sub-menu li { float:none; width:auto; flex:0 0 auto; }
.nolly-contributor-dashboard #dashboard-sub-menu li a { padding:14px 18px; font-size:15px; white-space:nowrap; }
.nolly-contributor-dashboard .nolly-contributor-heading { font-size:29px; line-height:1.25; margin:0 0 8px; font-weight:700; }
.nolly-contributor-dashboard .nolly-contributor-intro { color:#b7b7c4; margin-bottom:24px; line-height:1.6; }
.nolly-contributor-dashboard .nolly-contributor-filters { display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; margin-bottom:24px; }
.nolly-contributor-dashboard .nolly-contributor-filters .nolly-field { min-width:180px; flex:1 1 180px; max-width:260px; }
.nolly-contributor-dashboard .nolly-contributor-filters label { display:block; margin-bottom:7px; font-weight:600; }
.nolly-contributor-dashboard .nolly-contributor-filters input { min-height:46px; width:100%; }
.nolly-contributor-dashboard .nolly-filter-actions { display:flex; align-items:center; gap:16px; flex-wrap:wrap; padding-bottom:2px; }
.nolly-contributor-dashboard .nolly-filter-actions .btn { margin:0; }
.nolly-contributor-dashboard .nolly-stat-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; margin:0 0 22px; }
.nolly-contributor-dashboard .nolly-stat-card { background:#1e1d26; border:1px solid rgba(255,255,255,.09); border-radius:10px; padding:22px; }
.nolly-contributor-dashboard .nolly-stat-card strong { display:block; font-size:30px; font-weight:700; line-height:1.2; color:#fff; }
.nolly-contributor-dashboard .nolly-stat-card span { display:block; color:#bbb9c9; font-size:14px; margin-top:7px; }
.nolly-contributor-dashboard .nolly-stats-help { font-size:14px; color:#b7b7c4; line-height:1.65; }
.nolly-contributor-dashboard .nolly-film-table { margin-top:22px; }
.nolly-contributor-dashboard .nolly-film-table table { width:100%; }
.nolly-contributor-dashboard .nolly-film-table th { font-weight:700; }
.nolly-contributor-dashboard .nolly-film-table th, .nolly-contributor-dashboard .nolly-film-table td { padding:14px 16px; vertical-align:middle; }
@media(max-width:991px) {
  .nolly-contributor-dashboard .nolly-contributor-layout { gap:22px; }
  .nolly-contributor-dashboard #vayvo-progression-author-sidebar { width:220px; flex-basis:220px; }
  .nolly-contributor-dashboard #dashboard-sub-menu li a { padding:12px 13px; font-size:14px; }
}
@media(max-width:767px) {
  #content-pro.nolly-contributor-dashboard { padding:22px 0 48px; }
  .nolly-contributor-dashboard .nolly-contributor-layout { display:block; }
  .nolly-contributor-dashboard #vayvo-progression-author-sidebar { width:100%; margin:0 0 24px; }
  .nolly-contributor-dashboard #dashboard-sub-menu { gap:4px; }
  .nolly-contributor-dashboard #dashboard-sub-menu li { flex:1 1 auto; }
  .nolly-contributor-dashboard #dashboard-sub-menu li a { white-space:normal; padding:12px 9px; font-size:13px; }
  .nolly-contributor-dashboard .nolly-stat-grid { grid-template-columns:1fr; gap:10px; }
  .nolly-contributor-dashboard .nolly-contributor-filters .nolly-field { max-width:none; }
  .nolly-contributor-dashboard .nolly-contributor-heading { font-size:24px; }
}
</style>
@endsection

@section('content')
<div id="content-pro" class="nolly-contributor-dashboard">
    <div class="container custom-gutters-pro nolly-contributor-layout">
        <div id="vayvo-progression-author-sidebar">
            @include('partials.profile_aside', ['user' => $user])
        </div>
        <div id="vayvo-progression-author-content-container">
            @include('partials.profile_nav_link', ['user' => $user, 'current' => 'dashboard'])
            <h1 class="nolly-contributor-heading">{{ $user->type === 'casts' ? 'Actor Dashboard' : 'Filmmaker Dashboard' }}</h1>
            <p class="nolly-contributor-intro">Welcome, {{ $user->fullname() }}. See how your linked movies are performing. Only movies linked to your profile appear here.</p>
            @include('errors.errors')
            <form method="get" action="{{ route('actors.dashboard') }}" class="nolly-contributor-filters">
                <div class="nolly-field"><label for="stats-from">From</label><input id="stats-from" type="date" name="from" value="{{ $from }}" class="form-control"></div>
                <div class="nolly-field"><label for="stats-to">To</label><input id="stats-to" type="date" name="to" value="{{ $to }}" class="form-control"></div>
                <div class="nolly-filter-actions"><button type="submit" class="btn btn-primary">Apply dates</button> <a href="{{ route('actors.dashboard') }}">All time</a></div>
            </form>
            <div class="nolly-stat-grid">
                <div class="nolly-stat-card"><strong>{{ number_format($films->count()) }}</strong><span>Linked films</span></div>
                <div class="nolly-stat-card"><strong>{{ number_format($films->sum('clicks')) }}</strong><span>Film page clicks</span></div>
                <div class="nolly-stat-card"><strong>{{ number_format($films->sum('watches')) }}</strong><span>Watches started</span></div>
            </div>
            <p class="nolly-stats-help">These numbers represent activity recorded by Nollyflix, not third-party platforms. Clicks count film detail page visits, including repeat visits. Watches count playback starts once per player page session; pausing and resuming does not add another watch. Admin previews are excluded. These figures cover the website from this update onward.</p>
            @if($trackingStarted)<p>First recorded activity: {{ \Carbon\Carbon::parse($trackingStarted)->format('j M Y') }}.</p>@endif
            <div class="table-responsive nolly-film-table">
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
