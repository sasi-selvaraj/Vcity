<x-mail::message>
Hi {{$data->name}},

Use the following OTP to complete your Sign In.

<x-mail::button :url="''">
    {{$data->otp}}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
