<x-mail::message>
{!!  $body !!}

Thanks,<br>
{{ config('app.name') }}

<img style="display:none;" src="{{ route("tracking.openings", $mail) }}"/>
</x-mail::message>
