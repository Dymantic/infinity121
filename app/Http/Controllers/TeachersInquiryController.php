<?php

namespace App\Http\Controllers;

use App\Notifications\TeacherSignedUp;
use App\Teachers\TeacherInquiry;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TeachersInquiryController extends Controller
{

    public function show()
    {
        $formLabels = [
            'name'                     => trans('forms.name'),
            'email'                    => trans('forms.email'),
            'phone'                    => trans('forms.phone'),
            'age'                      => trans('forms.age'),
            'years_in_taiwan'          => trans('forms.years-in-taiwan'),
            'available_hours_per_week' => trans('forms.available-hours'),
            'teaching_experience'      => trans('forms.teaching-experience'),
            'message'                  => trans('forms.message'),
        ];

        return view('front.teachers.sign-up', [
            'labels' => $formLabels
        ]);
    }

    public function store()
    {
        request()->validate([
            'name'  => ['required'],
            'email' => ['email', 'required_without:phone'],
            'phone' => ['required_without:email'],
        ]);

        $inquiry = TeacherInquiry::create(request()->all());

        Notification::send(User::admins()->get(), new TeacherSignedUp($inquiry));

        return $inquiry;
    }
}
