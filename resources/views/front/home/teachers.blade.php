<div class="wave-blue-top px-6">
    <div class="max-w-5xl mx-auto pt-80">
        <p data-usher class="type-h1 max-w-2xl mb-6">{{ trans('home.teachers.heading') }}</p>
        <p class="type-b1 max-w-2xl">{{ trans('home.teachers.intro') }}</p>
        <div class="grid-3x my-20">
            @foreach($teachers as $teacher)
                @include('front.home.teacher-card')
            @endforeach
        </div>
    </div>
</div>
