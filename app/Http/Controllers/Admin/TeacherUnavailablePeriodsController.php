<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatedPeriodRequest;
use App\Teaching\UnavailablePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TeacherUnavailablePeriodsController extends Controller
{

    public function index()
    {
        return request()->user()->profile->unavailablePeriods->map->toArray();
    }

    public function store(DatedPeriodRequest $request)
    {
        $teacher = request()->user()->profile;

        $teacher->setUnavailablePeriod($request->starts(), $request->ends());
    }

    public function update(UnavailablePeriod $period, DatedPeriodRequest $request)
    {
        $period->update([
            'starts' => $request->starts(),
            'ends' => $request->ends(),
        ]);
    }

    public function delete(UnavailablePeriod $period)
    {
        $period->delete();
    }
}
