<div class="wave-white-bottom bg-mild-yellow py-20">
    <p class="type-h1 text-center text-hms-navy mb-12">{{ trans('home.about.heading') }}</p>
    <div class="max-w-2xl mx-auto type-b1 px-8 text-hms-navy text-center">
        @foreach(trans('home.about.text') as $paragraph)
        <p class="mb-6">{{ $paragraph }}</p>
        @endforeach
    </div>
    <div class="text-center my-20">
        <a href="" class="type-a2 text-hms-navy">{!! trans('home.about.link') !!}</a>
    </div>
    <div class="h-32"></div>
</div>
