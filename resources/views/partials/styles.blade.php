<meta charset="utf-8">
<title>{{ isset( $page_title) ?  $page_title .' |  '.config('app.name') : 'NollyFlix' }}</title>
<meta name="description" content="{{ isset($page_meta_description) ? $page_meta_description : $system_settings->meta_description }}">
<meta name="keywords" content="{{ isset($system_settings->meta_tag_keywords) ? $system_settings->meta_tag_keywords : 'Nollywood, Onlive movies, Rent movies online buy movies online, buy nollywood movies online' }}" />

<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-32x32.png" sizes="32x32" />
<link rel="icon" href="https://nollyflix.tv/favicons/cropped-nflix-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon-precomposed" href="https://nollyflix.tv/favicons/cropped-nflix-180x180.png" />
<meta name="msapplication-TileImage" content="https://nollyflix.tv/favicons/cropped-nflix-270x270.png" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="canonical" href="https://nollyflix.tv/">
<meta property="og:site_name" content="NollyFilx">
<meta property="og:url" content="https://nollyflix.tv">
<meta property="og:title" content=" NollyFlix tv">
<meta property="og:type" content="website">
<meta property="og:description" content="Watch nollywood movies online">
<script src='https://kit.fontawesome.com/a076d05399.js'></script> 
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@yield('page-css')

<link rel="stylesheet" href="/css/overide.css?version={{ str_random(6) }}">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">