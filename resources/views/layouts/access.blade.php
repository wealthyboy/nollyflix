<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('partials.styles')
	</head>

	

	<script>
		Window.user = {
			user: {!! auth()->check() ? auth()->user() : 0000 !!},
			loggedIn: {!! auth()->check() ? 1 : 0 !!},
			video: {!! isset($video) ? $video : 0 !!},
			settings: {!! isset($system_settings) ? $system_settings : '' !!},
			token: '{!! csrf_token() !!}'
		}
	</script>
	
	<body>
	   <div id="app">
	    @include('includes.header',['allow_search' => true])
		<section>
			@yield('content')
		</section>

		@php
			$showMobileSiteFooter = request()->routeIs('home') || request()->routeIs('cookie.policy');
		@endphp
		<footer id="footer-pro" class="{{ $showMobileSiteFooter ? '' : 'mobile-hide-site-footer' }}">
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

		
		<footer id="footer-pro" class="{{ $showMobileSiteFooter ? '' : 'mobile-hide-site-footer' }}">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">&copy;  {{ Config('app.name') }}  {{ date('Y') }}. All rights reserved.</div>
					</div><!-- close .col -->
					<div class="col-md">
						<ul class="social-icons-pro">
							<li class="instagram-color"><a href="https://www.instagram.com/Nollyflix2026" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on Instagram"><i class="fab fa-instagram"></i></a></li>
							<li class="twitter-color"><a href="https://x.com/Nollyflix2026" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on X"><span aria-hidden="true" style="font-family:Arial,sans-serif;font-size:18px;font-weight:700;line-height:1;">X</span></a></li>
							<li class="tiktok-color"><a href="https://www.tiktok.com/@Nollyflix_" target="_blank" rel="noopener noreferrer" aria-label="Nollyflix on TikTok"><i class="fab fa-tiktok"></i></a></li>
						</ul>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		</div>

		@include('includes.whatsapp-chat')
		
	
		<!-- Required Framework JavaScript -->
		<script src="/js/app.js?version={{ str_random(6) }}"></script><!-- Custom Document Ready JS -->
		@yield('page-scripts')
	</body>
</html>
