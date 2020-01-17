@component('mail::message')
# Teacher SignUp

Somebody has signed up to become a teacher. Details are below.

Name: {{ $inquiry['name'] }}
Email: {{ $inquiry['email'] ?? 'Not supplied' }}
Phone: {{ $inquiry['phone'] ?? 'Not supplied' }}
Age: {{ $inquiry['age'] ?? 'Not supplied' }}
Available hours per week: {{ $inquiry['available_hours_per_week'] ?? 'Not supplied' }}
Years in Taiwan: {{ $inquiry['years_in_taiwan'] ?? 'Not supplied' }}
Teaching Experience: {{ $inquiry['teaching_experience'] ?? 'Not supplied' }}
@endcomponent
