<?php

namespace App\Http\Controllers;

use App\Notifications\StudentSignedUp;
use App\Students\StudentInquiry;
use App\Teaching\Subject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class StudentsInquiryController extends Controller
{

    public function show()
    {
        $subjects = Subject::all()->map->forCurrentLang();

        $requiredCourse = Subject::where('slug', request('course'))->first();
        $course = $requiredCourse ? $requiredCourse->id : $subjects->first()['id'];

        $formLabels = [
            'name'            => trans('forms.name'),
            'email'           => trans('forms.email'),
            'phone'           => trans('forms.phone'),
            'age'             => trans('forms.age'),
            'course'          => trans('forms.course'),
            'english_ability' => trans('forms.english-ability'),
            'address'         => trans('forms.address'),
            'message'         => trans('forms.message'),
            'abilities'       => trans('forms.abilities'),
            'send'            => trans('forms.send'),
        ];

        $dialog = trans('dialogs.student_sign_up');

        return view('front.students.sign-up', [
            'subjects' => $subjects,
            'labels'   => $formLabels,
            'course'   => $course,
            'dialog'   => $dialog,
        ]);
    }

    public function store()
    {
        request()->validate([
            'name'       => ['required'],
            'email'      => ['email', 'required_without:phone'],
            'phone'      => ['required_without:email'],
            'subject_id' => ['required', 'exists:subjects,id']
        ]);

        $inquiry = StudentInquiry::create(request()->all());
        Notification::send(User::receivesAdminEmails()->get(), new StudentSignedUp($inquiry));

        return $inquiry;
    }
}
