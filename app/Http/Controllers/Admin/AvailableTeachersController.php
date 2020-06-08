<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class AvailableTeachersController extends Controller
{
    public function show()
    {
        return Profile::active()
                      ->teachers()
                      ->teachingIn(request('area_id'))
                      ->canTeach(request('subject_id'))
                      ->availableFor(request('lesson_blocks'))
                      ->get()->map(fn($t) => array_merge($t->toArray(),
                ['unavailable' => $t->unavailablePeriods->map->toArray()]))->toArray();
    }
}
