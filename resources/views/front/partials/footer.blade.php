<div class="bg-deep-navy text-white px-6">
    <div class="py-12">
        @include('svg.logos.logo_footer', ['classes' => 'h-8 block mx-auto'])
    </div>
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-center pb-12">
        <div class="w-full mb-12 md:mb-0 md:w-1/3 flex flex-col items-center">
            <p class="type-h2 mb-6">{{ trans('footer.sign_up') }}</p>
            @foreach($top_subjects as $subject)
                <p class="type-b2 w-40 text-center truncate">
                    <a class="hover:text-mustard"
                       href="{{ localUrl('/courses/' . $subject['slug']) }}">{{ $subject['title'] }}
                    </a>
                </p>
            @endforeach
            <p class="type-b2"><a class="hover:text-mustard" href="{{ localUrl("/courses") }}">{{ trans('footer.more') }} &gt;</a></p>
        </div>
        <div class="w-full mb-12 md:mb-0 md:w-1/3 flex flex-col items-center">
            <p class="type-h2 mb-6">{{ trans('footer.contact') }}</p>
{{--            <p class="type-b2">+886 4 084272</p>--}}
            <p class="type-b2">+886 983 644 123</p>
            <p class="type-b2"><a href="mailto:contact@infinity121.com">contact@infinity121.com</a></p>
            <p class="type-b2">
                <a href="https://line.me/R/ti/p/michaeljoyner">{{ trans('footer.line_app') }}: @infinity121</a>
                </p>
        </div>
        <div class="w-full mb-12 md:mb-0 md:w-1/3 flex flex-col items-center">
            <p class="type-h2 mb-6">
                <a href="{{ localUrl('/join-us') }}" class="hover:text-mustard arrow-link-left">{{ trans('footer.become_teacher') }}</a>
            </p>
        </div>
    </div>
    <p class="type-b2 text-center pb-4 mt-12">
        <span class="text-sky-blue">Beautifully Designed By </span>
        <a href="/" class="text-sunny-yellow hover:text-mustard">Dymantic Design</a>
    </p>
</div>
