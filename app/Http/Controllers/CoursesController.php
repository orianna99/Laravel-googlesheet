<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public static function searchCourse($short_name)
    {

        $course = Course::where('short_name', $short_name)->first();
        return $course->id;
    }
}
