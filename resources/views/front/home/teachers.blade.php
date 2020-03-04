<div class="wave-blue-top px-6">
    <div class="max-w-5xl mx-auto pt-80">
        <p data-usher class="type-h1 max-w-2xl mb-6">{{ trans('home.teachers.heading') }}</p>
        <p class="type-b1 max-w-2xl">{{ trans('home.teachers.intro') }}</p>
        <div class="little-slider md:flex justify-around my-20"
             data-flickity='{"cellAlign": "center", "contain": false, "watchCSS": true, "prevNextButtons": false}'
        >
            @foreach($teachers as $teacher)
                @include('front.home.teacher-card')
            @endforeach
        </div>
{{--        <div class="text-center">--}}
{{--            <a href="{{ localUrl('/teachers') }}" class="btn btn-dark">{!! trans('home.teachers.button') !!}</a>--}}
{{--        </div>--}}
    </div>
</div>
