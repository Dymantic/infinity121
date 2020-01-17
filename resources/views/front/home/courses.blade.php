<div class="wave-blue-bottom px-6">
    <div class="max-w-5xl mx-auto">
        <p class="type-h1 text-hms-navy max-w-2xl mb-6">{!! trans('home.courses.heading') !!}</p>
        <p class="type-b1 text-hms-navy max-w-2xl">{!! trans('home.courses.intro') !!}</p>
        <div class="my-32 flex flex-col md:flex-row justify-between items-center md:items-stretch">
            @foreach($subjects as $subject)
                @include('front.home.course-card')
            @endforeach
        </div>
        <div class="text-center">
            <a href="" class="btn btn-dark">{!! trans('home.courses.button') !!}</a>
        </div>
    </div>
    <div class="h-80"></div>
</div>
