<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoggedLessonsController extends Controller
{
    public function index()
    {
        return Lesson::logged()
            ->latest()
            ->get()->map->presentForAdmin();
    }
}
