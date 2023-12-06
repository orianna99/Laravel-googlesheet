<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Sheets;

class GoogleSheetController extends Controller
{
    public function index()
    {
        $shhet = Sheets::spreadsheet('1UbKrlXgTkBfFUaYCqABK6Kq0Gmg8-GYARZvYNHxp1og')->sheet('datos')->get();

        $header = $shhet->pull(0);
        $values = Sheets::collection($header, $shhet);
        $students = array_values($values->toArray());
        StudentController::managerStudentsData($students);

        return response()->json('Datos cargados');
    }
}
