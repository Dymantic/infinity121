@extends('admin.base')

@section('content')
<div class="mt-20 max-w-lg mx-auto shadow p-8">
    <p class="text-3xl mb-8">Please Login</p>
    <form action="/admin/login" method="POST">
        {!! csrf_field() !!}
        <div class="my-4">
            <label for="email" class="uppercase text-sm tracking-wide">email</label>
            <input class="block h-8 pl-2 border w-full mt-2" type="text" name="email" id="email">
        </div>
        <div class="my-4">
            <label class="uppercase text-sm tracking-wide" for="password">Password</label>
            <input class="block h-8 pl-2 border w-full mt-2" type="password" name="password" id="password">
        </div>
        <div class="my-4">
            <label class="uppercase text-sm tracking-wide" for="remember">Remember me: </label>
            <input type="checkbox" name="remember" id="remember">
        </div>
        <div>
            <button type="submit" class="btn btn-indigo">Login</button>
        </div>
        <p class="mt-4 text-right">
            <a class="no-underline hover:underline text-indigo-500" href="/password/reset">
                I forgot my password
            </a>
        </p>
    </form>
</div>
@endsection