<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('partials.styles')
	</head>

	<script>
		Window.content_owner = {
			user: {!! auth()->check() ? auth()->user() : null !!}
		}
	</script>
	<body>
		<header id="masthead-pro" class="sticky-header"><!-- Remove sticky-header class to remove sticky header -->
			<div class="header-container">
				<h1><a href="/"><img src="{{ $system_settings->logo_path() }}" alt="Nolly Flix Logo"></a></h1>

				<nav id="site-navigation-pro">
					<ul class="sf-menu">
					   @foreach( $global_categories   as  $category)
							<li class="normal-item-pro ">
						    	<a href="/browse/category/{{ $category->slug }}">{{ $category->name }}</a>
						    </li>
						@endforeach
						<li class="normal-item-pro">
							<a href="/browse/casta">Actors/Actress</a>
						</li>
						<li class="normal-item-pro">
							<a href="/browse/filmakers">Film Makers</a>
						</li>
					</ul>
				</nav>
				
				<!--div id="header-btn-right">
					<button class="btn btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button>
				</div-->
					
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
				@if (auth()->check()) 
				<div class="header-user-profile" id="header-user-profile">
					<div id="header-user-profile-click" class="noselect header-user-profile-click">
						<img src="/images/profile_icon.png" alt="Suzie">
						<div id="header-username">{{ '' }}</div><i class="fas fa-angle-down"></i>
					</div><!-- close #header-user-profile-click -->
					<div class="header-user-profile-menu" id="header-user-profile-menu">
						<ul>
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
				@else
				<div class="header-user-profile" id="header-user-profile">
				   <a href="/login" class="btn p-1 login-btn rounded-0 pr-3"  id=""><i class="fas fa-sign-in-alt"></i>Login </a>
	            </div>
				@endif
				
		
				<div id="progression-studios-header-search-icon" class="noselect cursor-pointer">
					<a href="#"><i class="fas fa-search mt-4 fa-1x"></i> </a>
				</div>
				

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
				    @foreach( $global_categories   as  $category)
						<li class="normal-item-pro">
							<a href="/browse/category/{{ $category->slug }}">{{ $category->name }}</a>
						</li>
					@endforeach
					<li class="normal-item-pro">
						<a href="/browse/casta">Actors/Actress</a>
					</li>
					<li class="normal-item-pro">
						<a href="/browse/filmakers">Film Makers</a>
					</li>
				</ul>
				<!--button class="btn btn-mobile-pro btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" >Login</button-->
				
				<div id="search-mobile-nav-pro">
					<input type="text" placeholder="Search for Movies or TV Series" aria-label="Search">
				</div>
				
			</nav>
			<div id="progression-studios-header-shadow"></div>
		</header>
		
		<section>
			@yield('content')
		</section>

		<footer id="footer-pro">
		    <div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-12">
					    <ul class="footer-links">
						   @foreach($footer_info as $info)
							   <li class=""><a href="{{ $info->link }}" >{{ title_case($info->name) }}</a></li>
							@endforeach
							@if ( auth()->check() && auth()->user()->isAdmin() )
							    <li class=""><a target="_blank" href="/admin" >Go to Admin</a></li>
							@endif
							
						</ul>
					</div>
				</div>
	        </div>
		</footer>

		
		<footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">&copy;  {{ Config('app.name') }}  {{ date('Y') }}. All rights reserved.</div>
					</div><!-- close .col -->
					<div class="col-md">
						<ul class="social-icons-pro">
							<li class="facebook-color"><a  href="{{ $system_settings->facebook_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li class="twitter-color"><a   href="{{  $system_settings->twitter_link }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li class="instagram-color"><a href="{{  $system_settings->instagram_link }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li class="youtube-color"><a   href="{{  $system_settings->youtube_link }}" target="_blank"><i class="fab fa-youtube"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="home.html#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
	
		

		<!-- Required Framework JavaScript -->
		<script src="/js/libs/jquery-3.5.1.min.js"></script><!-- jQuery -->
		<script src="/js/libs/popper.min.js"></script><!-- Bootstrap Popper/Extras JS -->
		<script src="/js/libs/bootstrap.min.js"></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->
		<!-- Additional Plugins and JavaScript -->
		<script src="/js/navigation.js"></script><!-- Header Navigation JS Plugin -->
		<script src="/js/bootstrap-notify.js"></script><!-- Header Navigation JS Plugin -->
		<script src="/js/jquery.flexslider-min.js"></script><!-- FlexSlider JS Plugin -->	
		<script src="/js/owl.carousel.min.js"></script><!-- Carousel JS Plugin -->
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
		<script src="/js/scripts.js?version={{ str_random(6) }}"></script><!-- Custom Document Ready JS -->
	</body>
</html>
