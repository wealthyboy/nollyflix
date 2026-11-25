@extends('layouts.access')

@section('content')

<div class="h-100 d-none searching">
    <div class="">                    
        <p class="large">Searching.....</p>
    </div>
</div>
<section class="section-content nolly-xd-directory">
  <div id="content-pro">
    <div class="container-fluid custom-gutters-pro nolly-xd-page-wrap">
      <header class="nolly-xd-page-hero">
        <span class="nolly-xd-eyebrow">NOLLYFLIX TALENT</span>
        <h1>{{ $cast_title }}</h1>
        <p>{{ $description }}</p>
        <span class="nolly-xd-accent-line" aria-hidden="true"></span>
      </header>
      @if($users->count())
      <div class="nolly-xd-people-grid">
        @foreach($users as $user)
          <a class="nolly-xd-person" href="/{{ $user->username }}" aria-label="View {{ $user->fullname() }}">
            <div class="nolly-xd-person-image"><img loading="lazy" src="{{ $user->m_path() }}" alt="{{ $user->fullname() }}"></div>
            <h2>{{ $user->fullname() }}</h2>
            <span>View profile <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
          </a>
        @endforeach
      </div>
      @else
        <div class="nolly-xd-empty">No profiles found yet.</div>
      @endif
    </div>
  </div>
</section>

@include('includes.search')
@endsection
