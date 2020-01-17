<div class="wave-yellow-bottom px-6">
    <div class="max-w-5xl mx-auto pb-80">
        <p class="type-h1 text-hms-navy max-w-2xl mb-6">{{ trans('home.teachers.heading') }}</p>
        <p class="type-b1 text-hms-navy max-w-2xl">{{ trans('home.teachers.intro') }}</p>
        <div class="grid-3x my-20">
            @foreach($teachers as $teacher)
                @include('front.home.teacher-card')
            @endforeach
        </div>
    </div>
</div>
