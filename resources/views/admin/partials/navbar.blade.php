<div class="flex justify-between items-center bg-indigo-500 px-4 h-16">
    <div class="flex-1 flex items-center">
        <p class="font-black text-white">Infinity121</p>
    </div>

    <div class="flex justify-end items-center">
    <dropdown-menu v-cloak
                   name="{{ auth()->user()->name }}"
                   class="text-white h-12 flex items-center h-16">
        <div slot="dropdown_content"
             class="pt-3">
            @include('admin.auth.logout-form')
{{--            <a href=""--}}
{{--               class="text-black no-underline hover:text-indigo-500 pb-3 block">Logout</a>--}}
        </div>
    </dropdown-menu>
    </div>
</div>