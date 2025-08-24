@component('mail::message')
    # Hi {{ $subscription->user->name }}

    Great news! Your **{{ $subscription->plan_name }}** subscription has been **renewed**.
    New end date: **{{ $subscription->end_date->toFormattedDateString() }}**.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
