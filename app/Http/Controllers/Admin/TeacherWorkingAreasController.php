<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherWorkingAreasController extends Controller
{
    public function store()
    {
        request()->validate([
            'area_ids' => ['present', 'array'],
            'area_ids.*' => ['exists:areas,id']
        ]);

        $teacher = request()->user()->profile;

        $teacher->setWorkingAreas(request('area_ids'));
    }
}
