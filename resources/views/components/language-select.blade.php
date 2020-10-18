<div>
    <div class="hidden lg:block group relative">
        <button
            class="hidden lg:flex items-center type-a1 tracking-wide ml-4 mr-6 focus:outline-none dropdown-btn capitalize">
            @include('svg.icons.globe', ['classes' => $icon_colour . ' fill-current h-6 mr-2'])
            <span class="mr-2">{{ $current()['name'] }}</span>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 11.92" class="w-3 ml-1 inline">
                <path class="stroke-current" fill="none" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="4" d="M22 2L12 9.92 2 2"/>
            </svg>
        </button>
        <div class="nav-more hidden group-hover:block absolute center-x w-32">
            <div class="relative center-x top-8 up-pointer bg-hms-navy rounded px-4 pb-4 pt-2 shadow-button">
                @foreach($translations as $link)
                    <a class="text-white hover:text-sunny-yellow block text-center font-bold my-3" href="{{ translatedUrl(Request::path(), $link['locale']) }}">{{ $link['name'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="block lg:hidden px-4 text-white">
        @foreach($translations as $link)
        <a class="text-white hover:text-sunny-yellow block font-bold mb-4" href="{{ translatedUrl(Request::path(), $link['locale']) }}">{{ $link['name'] }}</a>
        @endforeach
    </div>
</div>



