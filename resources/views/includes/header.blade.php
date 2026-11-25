<header id="masthead-pro" class="sticky-header {{  isset($mobile)  ? 'd-none': '' }}"><!-- Remove sticky-header class to remove sticky header -->
    <div class="header-container">
        <h1><a href="/"><img src="{{ $system_settings->logo_path() }}" alt="Nolly Flix Logo"></a></h1>

        <nav id="site-navigation-pro">
            <ul class="sf-menu">
                <li class="normal-item-pro"><a href="/">Home</a></li>
                <li class="normal-item-pro"><a href="/browse">Movies</a></li>
                {{-- Featured category links displayed in a consistent order. --}}
                @php
                    $featuredMenuCategories = [
                        'tv-series' => 'TV Series',
                        'nollywood-oldies' => 'Nollywood Oldies',
                        'in-cinemas-now' => 'In Cinemas Now',
                    ];
                    $featuredMenuSlugs = [];
                    foreach ($featuredMenuCategories as $defaultSlug => $label) {
                        $foundCategory = $global_categories->first(function ($category) use ($defaultSlug, $label) {
                            return \Illuminate\Support\Str::slug($category->name) === $defaultSlug
                                || \Illuminate\Support\Str::slug($category->slug) === $defaultSlug
                                || strcasecmp(trim($category->name), $label) === 0;
                        });
                        $featuredMenuSlugs[$defaultSlug] = $foundCategory ? $foundCategory->slug : $defaultSlug;
                    }
                @endphp
                @foreach ($featuredMenuCategories as $menuSlug => $menuLabel)
                    <li class="normal-item-pro"><a href="{{ url('browse/category/'.$featuredMenuSlugs[$menuSlug]) }}">{{ $menuLabel }}</a></li>
                @endforeach
                @foreach ($global_categories as $category)
                    @if (!in_array(\Illuminate\Support\Str::slug($category->name), array_keys($featuredMenuCategories), true)
                        && !in_array(\Illuminate\Support\Str::slug($category->slug), array_keys($featuredMenuCategories), true))
                        <li class="normal-item-pro"><a href="{{ url('browse/category/'.$category->slug) }}">{{ $category->name }}</a></li>
                    @endif
                @endforeach
                <li class="normal-item-pro">
                    <a href="/browse/a/casts">Actors/Actress</a>
                </li>
                <li class="normal-item-pro">
                    <a href="/browse/a/filmakers">Film Makers</a>
                </li>
            </ul>
        </nav>
        
        <!--div id="header-btn-right">
            <button class="btn btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button>
        </div-->
            
        <div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
        <div class="header-user-profile prfDrpdwn {{ !auth()->check() ? 'd-none' : '' }}" id="header-user-profile">
            <div id="header-user-profile-click" class="noselect header-user-profile-click">
            @php
                // Prefer a genuine uploaded photo, but never point the header to a missing file.
                $headerAvatarFallback = asset('images/default-contributor-avatar.jpg');
                $headerAvatar = $headerAvatarFallback;
                if (auth()->check() && !empty(auth()->user()->image)) {
                    $headerPhoto = basename((string) auth()->user()->image);
                    if ($headerPhoto !== '' && is_file(public_path('images/users/m/' . $headerPhoto))) {
                        $headerAvatar = asset('images/users/m/' . $headerPhoto);
                    }
                }
            @endphp
            <img src="{{ $headerAvatar }}"
                 alt="{{ auth()->check() ? auth()->user()->fullname() : 'Profile' }}"
                 onerror="this.onerror=null;this.src='{{ $headerAvatarFallback }}';">
                <div id="header-username">{{ '' }}</div><i class="fas fa-angle-down"></i>
            </div><!-- close #header-user-profile-click -->
            <div class="header-user-profile-menu" id="header-user-profile-menu">
                <ul>
                    @if(auth()->check() && in_array(auth()->user()->type, ['casts', 'filmakers'], true))
                        <li><a href="{{ route('actors.dashboard') }}">My Movies Dashboard</a></li>
                    @endif
                    <li><a href="{{ route('profile.index') }}"><i class="fa fa-user-circle"></i>My Profile</a></li>
                    <li><a href="{{ route('profiles.watchlists') }}"><i class="fa fa-list-ul"></i>My Watchlist</a></li>
                    <li>
                    <a class="" href="/logout"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt left"></i>
                                            
                                            Logout
                                        </a>
                                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                    
                    </li>
                </ul>
            </div><!-- close #header-user-profile-menu -->
        </div><!-- close #header-user-profile -->


        <div class="header-user-profile prfLBtn {{ auth()->check() ? 'd-none' : '' }}" id="header-user-profile">
            <a href="/register" class="btn nollyflix-signup-btn">Sign up</a>
            <a href="/login" class="btn p-1 login-btn rounded-0 pr-3"  id=""><i class="fas fa-sign-in-alt"></i>Login </a>
        </div>

        @if(isset($allow_search))
            <div id="progression-studios-header-search-icon" class="noselect cursor-pointer">
                <a href="#"><i class="fas fa-search mt-4 fa-1x"></i> </a>
            </div>
        @endif
        
        

        
        

        <div id="video-search-header">
            <div class="">
                <input type="text" class="search-input" placeholder="Search for Movies or TV Series" aria-label="Search" id="main-text-field">
                <span  style=""  class="spinner-border d-none search-spinner spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="close-icon"><i class="fas fa-times"></i></span>
            </div><!-- close .container -->
        </div><!-- close .video-search-header -->
        
        <div class="clearfix"></div>
    </div><!-- close .header-container -->
    
    <nav id="mobile-navigation-pro">
        <ul id="mobile-menu-pro">
            <li class="normal-item-pro"><a href="/">Home</a></li>
            @foreach ($featuredMenuCategories as $menuSlug => $menuLabel)
                <li class="normal-item-pro"><a href="{{ url('browse/category/'.$featuredMenuSlugs[$menuSlug]) }}">{{ $menuLabel }}</a></li>
            @endforeach
            @foreach ($global_categories as $category)
                @if (!in_array(\Illuminate\Support\Str::slug($category->name), array_keys($featuredMenuCategories), true)
                    && !in_array(\Illuminate\Support\Str::slug($category->slug), array_keys($featuredMenuCategories), true))
                    <li class="normal-item-pro"><a href="{{ url('browse/category/'.$category->slug) }}">{{ $category->name }}</a></li>
                @endif
            @endforeach
            <li class="normal-item-pro">
                <a href="/browse/a/casts">Actors/Actress</a>
            </li>
            <li class="normal-item-pro">
                <a href="/browse/a/filmakers">Film Makers</a>
            </li>
        </ul>
        <!--button class="btn btn-mobile-pro btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button-->
        
        <div id="search-mobile-nav-pro">
            <input type="text" placeholder="Search for Movies " aria-label="Search">
        </div>
        
    </nav>
    <div id="progression-studios-header-shadow"></div>
</header>
