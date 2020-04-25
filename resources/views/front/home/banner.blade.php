<div class="min-h-screen bg-mild-yellow w-full flex flex-col justify-center items-center home-banner">
    <p class="pt-32 type-h1 px-4 text-center">{{ trans('home.banner.heading') }}</p>
    <p class="mt-12 type-b1 px-4 text-center">{{ trans('home.banner.text') }}</p>
    <a class="my-20 md:my-32 btn btn-dark" href="{{ localUrl('/students/sign-up') }}">{!! trans('home.banner.button') !!}</a>
    <a href="" class="chevron mb-20" data-jump data-jump-target="#about">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 11.92" class="w-8">
            <path class="stroke-current" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M22 2L12 9.92 2 2" data-name="Layer 1"/>
        </svg>
    </a>
</div>
