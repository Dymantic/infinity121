<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    @section('title')
        <title>Infinity121 | Admin </title>
    @show
    <link rel="stylesheet"
          href="{{ mix('css/app.css') }}"/>
    <meta id="csrf-token-meta"
          name="csrf-token"
          content="{{ csrf_token() }}">
    <META NAME="ROBOTS"
          CONTENT="NOINDEX, NOFOLLOW">
    @yield('head')
</head>
<body class="">
<script>
    window.current_user = {
        name: "{{ $user->name }}",
        email: "{{ $user->email }}",
        is_teacher: {{ $user->is_teacher ? 'true' : 'false' }},
        is_admin: {{ $user->is_admin ? 'true' : 'false' }},
    };
</script>
<div id="app">
    <navbar></navbar>
    <router-view></router-view>
    <notification-hub></notification-hub>
</div>

{{--<div class="main-footer"></div>--}}
<script src="{{ mix('js/app.js') }}"></script>
{{--@include('admin.partials.flash')--}}
@yield('bodyscripts')

</body>
</html>
