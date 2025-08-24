@component('mail::message')
    # Hi {{ $subscription->user->name }}

    Your **{{ $subscription->plan_name }}** subscription expires on **{{ $subscription->end_date->toFormattedDateString() }}**.

    If you want to keep it active, please renew before it expires.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
