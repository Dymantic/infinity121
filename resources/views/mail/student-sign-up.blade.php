@component('mail::message')
# Student Sign-up

A student is looking for lessons. Details follow:

  Name: {{ $inquiry['name'] }}
  Email: {{ $inquiry['email'] ?? 'Not supplied' }}
  Phone: {{ $inquiry['phone'] ?? 'Not supplied' }}
  Age: {{ $inquiry['age'] ?? 'Not supplied' }}
  English Ability: {{ $inquiry['english_ability'] ?? 'Not supplied' }}
  Course: {{ $inquiry['course'] ?? 'Not supplied' }}
  Address: {{ $inquiry['address'] ?? 'Not supplied' }}
  Message: {{ $inquiry['message'] ?? 'Not supplied' }}

@endcomponent
