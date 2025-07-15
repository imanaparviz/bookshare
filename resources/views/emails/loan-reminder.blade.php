{{-- resources/views/emails/loan-reminder.blade.php --}}
@component('mail::message')
# {{ $subject }}

Hallo {{ $notifiable->name }}!

{{ $mainMessage }}

@component('mail::panel')
**📚 Buchdetails:**
- **Titel:** {{ $loan->book->title }}
- **Autor:** {{ $loan->book->author }}
- **Rückgabedatum:** {{ $loan->due_date->format('d.m.Y') }}
- **Verliehen von:** {{ $loan->lender->name }}
@endcomponent

{{ $additionalInfo }}

@if($loan->lender->email)
@component('mail::panel')
**📞 Kontaktinformationen:**
- **Email:** {{ $loan->lender->email }}
@if($loan->contact_info)
- **Zusätzliche Kontaktinfo:** {{ $loan->contact_info }}
@endif
@endcomponent
@endif

@component('mail::button', ['url' => route('loans.show', $loan)])
Ausleihe anzeigen
@endcomponent

@component('mail::subcopy')
Du kannst auch über die Conversations-Funktion direkt mit dem Buchbesitzer kommunizieren.
@endcomponent

Vielen Dank für die Nutzung von BookShare!

Mit freundlichen Grüßen,<br>
{{ config('app.name') }} Team
@endcomponent 