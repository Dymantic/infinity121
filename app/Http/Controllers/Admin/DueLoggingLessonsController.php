<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DueLoggingLessonsController extends Controller
{
    public function index()
    {
        return Lesson::requiresLogging()
                     ->orderBy('lesson_date')
                     ->get()->map->presentForAdmin();
    }
}
