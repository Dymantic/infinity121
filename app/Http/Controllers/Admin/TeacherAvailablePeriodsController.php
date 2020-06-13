<?php

namespace App\Http\Controllers\Admin;

use App\Calendar\TimePeriod;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvailablePeriodsRequest;
use Illuminate\Http\Request;

class TeacherAvailablePeriodsController extends Controller
{

    public function show()
    {
        $teacher = request()->user()->profile;

        return $teacher->availablePeriodsSummary();
    }

    public function store(AvailablePeriodsRequest $request)
    {
        $teacher = $request->teacher();

        $teacher->setAvailabilityForDay($request->asDay());
    }
}
