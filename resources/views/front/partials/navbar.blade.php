<div
    class="main-nav flex h-16 {{ $light ?? false ? 'text-hms-navy' : 'bg-hms-navy text-white' }} fixed w-full top-0 left-0 pl-4 lg:pl-8 pr-4 lg:pr-12 justify-between items-center">
    <div>
        <a href="{{ localUrl("/") }}" class="no-underline">
            @include('svg.logos.logo_full', ['classes' => 'h-6 text-mustard hover:text-sunny-yellow'])
        </a>
    </div>
    <div
        class="nav-drawer flex flex-col lg:flex-row fixed lg:static top-16 w-full lg:w-auto min-h-full lg:min-h-0 left-0 capitalize">
        <a class="type-a1 tracking-wide ml-4 mt-12 mb-4 lg:mb-0 lg:mt-0 hover:text-mustard"
           href="{{ localUrl("/courses") }}">{{ trans('navbar.courses') }}</a>
        <a class="type-a1 tracking-wide ml-4 mb-4 lg:mb-0 hover:text-mustard"
           href="{{ localUrl("/students/sign-up") }}">{{ trans('navbar.sign-up') }}</a>
        <div class="hidden lg:block group relative">
            <button
                class="hidden lg:inline-block type-a1 tracking-wide ml-4 mr-6 focus:outline-none dropdown-btn capitalize">{{ trans('navbar.more') }}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 11.92" class="w-3 ml-1 inline">
                    <path class="stroke-current" fill="none" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="4" d="M22 2L12 9.92 2 2"/>
                </svg>
            </button>
            <div class="nav-more hidden group-hover:block absolute center-x w-48">
                <div class="relative center-x top-8 up-pointer bg-hms-navy rounded px-4 pt-2 shadow-button">
                    @include('front.partials.subnav-links')
                </div>
            </div>
        </div>
        <div class="lg:hidden">
            @include('front.partials.subnav-links')
        </div>

        <x-language-select :light="$light ?? false"></x-language-select>
    </div>
    <button class="lg:hidden nav-trigger hover:text-mustard">
        <svg class="w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path
                d="M16.4 9H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1zm0 4H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1zM3.6 7h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1z"/>
        </svg>
    </button>
</div>
