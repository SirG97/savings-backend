@component('mail::message')
# {{ $emailData->subject }}

@foreach($emailData->lines as $line)
{!! $line !!}
@endforeach
@if (isset($emailData->highlightText))
@component('mail::panel', ['style' => 'background-color: #f0f8ff; border-radius: 0.5rem; padding: 16px;text-align: center'])
<div style="text-align: center;">
{{ $emailData->highlightText }}
</div>
@endcomponent
@endif
@if ($emailData->action)
@component('mail::button', ['url' => $emailData->action_url])
{{ $emailData->action_text }}
@endcomponent
@endif

@if ($emailData->remark)
{{ $emailData->remark }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
