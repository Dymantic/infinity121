<div class="wave-white-bottom bg-mild-yellow py-20" id="about">
    <p class="type-h1 px-6 text-center mb-12" data-usher>{{ trans('home.about.heading') }}</p>
    <div class="max-w-3xl mx-auto type-b1 px-8 text-left md:text-center">
        @foreach(trans('home.about.text') as $paragraph)
        <p class="mb-6">{{ $paragraph }}</p>
        @endforeach
    </div>
    <div class="text-center my-20">
        <a href="{{ localUrl('/about-us') }}" class="hover:text-hms-navy type-a2 arrow-link-left">{!! trans('home.about.link') !!}</a>
    </div>
    <div class="h-32"></div>
</div>
