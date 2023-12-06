<?php

namespace App\Http\Controllers;

use App\Models\StudentCourses;
use Illuminate\Http\Request;

class StudentCoursesController extends Controller
{
    public static function create($student_id, $course_id)
    {
        StudentCourses::create([
            'student_id' => $student_id,
            'course_id' => $course_id
        ]);

        return 'success';
    }
}
