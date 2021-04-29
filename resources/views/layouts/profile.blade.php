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
		@include('includes.header')
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
		
		<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		</div>
		
	
		<!-- Required Framework JavaScript -->
		<script src="/js/app.js?version={{ str_random(6) }}"></script><!-- Custom Document Ready JS -->
		@yield('page-scripts')
	</body>
</html>
