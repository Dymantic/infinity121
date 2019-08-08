@extends('admin.base')

@section('content')
    <section class="flex justify-between items-center py-8 max-w-4xl mx-auto">
        <h1 class="flex-1 text-5xl font-bold">{{ $user->name }}</h1>
        <div class="flex justify-end items-center">

        </div>
    </section>
    <section class="my-16 mx-auto max-w-4xl">
        <h2 class="font-bold text-3xl">User Details</h2>
        <div class="p-4 bg-gray-100 shadow flex justify-between mt-8">
            <div class="">
                <p class="form-label">Name</p>
                <p>{{ $user->name }}</p>
            </div>
            <div class="">
                <p class="form-label">Email</p>
                <p>{{ $user->email }}</p>
            </div>
            <div class="">
                <p class="form-label">Roles</p>
                <p>@if($user->is_admin) Admin @endif @if($user->is_teacher) Teacher @endif</p>
            </div>
        </div>
    </section>
@if($user->is_teacher)
    <section class="my-16 mx-auto max-w-4xl">
        <h2 class="font-bold text-3xl">Public Profile</h2>
        <p><strong>Name: </strong>{{ $user->profile->name }}</p>
    </section>
@endif
@endsection