@extends('admin.base')

@section('content')
<div class="max-w-lg mx-auto mt-20 shadow p-8">
    <p class="text-3xl mb-8">Forgot your password? No worries.</p>
    <p class="mb-8">Fill out the email address you use to log in and we will send you a link. Follow that and you'll be back in business in no time!</p>
    <form action="/password/email" method="POST">
        @error('email')
            <p class="text-red-400">That email does not match our records. Please double check if you have the correct email address.</p>
        @enderror

        {!! csrf_field() !!}
        <div class="my-4">
            <label for="email" class="form-label" >email</label>
            <input class="input-text" type="text" value="{{ old('email') }}" name="email" id="email">
        </div>
        <div>
            <button type="submit" class="btn btn-navy">Send request</button>
        </div>
    </form>
    @if (session('status'))
    <div class="border border-indigo-500 p-4 mt-8" role="alert">
        {{ session('status') }}
    </div>
    @endif
</div>
@endsection
