@component('mail::message')
# Welcome aboard

You have been registered as an admin user of Infinity121. You may log in using the following details.

email: {{ $email }}
password: {{ $password }}

Once you have logged in, you will need to reset your password to something only you know, and that hasn't been shared.

@component('mail::button', ['url' => $url])
    Log in
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent