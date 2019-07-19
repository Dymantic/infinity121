<form action="/admin/logout" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="text-black hover:text-indigo-500">Logout</button>
</form>