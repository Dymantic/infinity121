@extends('admin.base')

@section('content')
    <div class="max-w-lg mx-auto mt-20 p-8">
        <p class="text-3xl mb-8">Reset Your Password</p>

        <form method="POST"
              action="{{ route('password.update') }}">
            @csrf

            <input type="hidden"
                   name="token"
                   value="{{ $token }}">

            <div class="my-4">
                <label for="email"
                       class="uppercase tracking-wide text-sm">Email</label>

                <input id="email"
                       type="email"
                       class="border h-8 pl-2 block w-full @error('email') border-red-400 @enderror"
                       name="email"
                       value="{{ $email ?? old('email') }}"
                       required
                       autocomplete="email"
                       autofocus>

                @error('email')
                <span class="text-red-400"
                      role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="my-4">
                <label for="password"
                       class="uppercase tracking-wide text-sm">Password</label>

                <input id="password"
                       type="password"
                       class="border h-8 pl-2 block w-full  @error('password') border-red-400 @enderror"
                       name="password"
                       required
                       autocomplete="new-password">

                @error('password')
                <span class="text-red-400"
                      role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="my-4">
                <label for="password-confirm"
                       class="uppercase tracking-wide text-sm">Confirm password</label>

                <input id="password-confirm"
                       type="password"
                       class="border h-8 pl-2 block w-full"
                       name="password_confirmation"
                       required
                       autocomplete="new-password">
            </div>

            <div class="my-4">
                <button type="submit"
                        class="btn btn-indigo">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
@endsection