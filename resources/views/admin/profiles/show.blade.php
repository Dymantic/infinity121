@extends('admin.base')

@section('content')
    <div class="max-w-4xl mx-auto">
        <section class="flex justify-between items-center py-8">
            <h1 class="flex-1 text-5xl font-bold">My Profile</h1>
            <div class="flex justify-end items-center">

            </div>
        </section>
        <section>
            <p>Name: {{ $profile->name }}</p>
            <p>Nationality: {{ $profile->nationality }}</p>
            <p>Nationality: {{ $profile->nationality }}</p>
            <p>Teaching Experience {{ $profile->experience }}</p>
            <p>Qualifications: {{ $profile->qualifications }}</p>
            <p>Chinese ability: {{ $profile->chinese_ability }}</p>
            <p>Bio: {{ $profile->bio }}</p>
        </section>
    </div>
@endsection