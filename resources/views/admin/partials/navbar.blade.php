<div class="flex justify-between items-center bg-indigo-500 px-4 h-16">
    <div class="flex-1 flex items-center">
        <p class="font-black text-white">Infinity121</p>
    </div>

    <div class="flex justify-end items-center">
        <a href="/admin/pages/subjects" class="text-white px-4 mr-4 no-underline hover:underline">Subjects</a>
        <a href="/admin/pages/users" class="text-white px-4 mr-4 no-underline hover:underline">Users</a>
    <dropdown-menu v-cloak
                   name="{{ auth()->user()->name }}"
                   class="text-white h-12 flex items-center h-16">
        <div slot="dropdown_content"
             class="pt-3">
            <a href="/admin/pages/me/profile#/show"
               class="text-black no-underline hover:text-indigo-500 pb-3 block">My Profile</a>
            @include('admin.auth.logout-form')

        </div>
    </dropdown-menu>
    </div>
</div>