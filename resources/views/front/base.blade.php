<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}">

<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> @yield('title', 'Infinity Training Corporation') </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700&display=swap" rel="stylesheet">

        <x-alt-lang-links></x-alt-lang-links>

        <link rel="stylesheet" href="{{ mix('css/front.css') }}">

        @yield('head')
</head>

<body class="font-sans {{ $bodyClasses ?? '' }} antialiased text-deep-navy">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->
<div id="app">
    <div class="h-16 m-0 p-0 nav-shadow absolute"></div>
    @yield('content')
    @include('front.partials.footer')
    @include('front.partials.navbar')
</div>
@yield('bodyscripts')
<script src="{{ mix("js/front.js") }}"></script>
<script>
    window.ga = function () {
        ga.q.push(arguments)
    };
    ga.q = [];
    ga.l = +new Date;
    ga('create', '{{ config('services.google.tracking') }}', 'auto');
    ga('send', 'pageview')
</script>
<script src="https://www.google-analytics.com/analytics.js" async defer></script>
</body>

</html>
