<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCourses;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public static function create($student)
    {
        $newStudent = Student::create([
            'name' => $student['name'],
            'email' => $student['email'],
            'phone' => $student['phone'],
            'document' => $student['document'],
            'classroom_user' => $student['classroom_user'],
        ]);

        return $newStudent->id;
    }

    public static function managerStudentsData($students)
    {
        foreach ($students as $student) {
            $nombreCompletoCliente = $student['NOMBRE COMPLETO CLIENTE'];
            $telefono = $student['TELÉFONO'];
            $correo = $student['CORREO'];
            $usuario_aula = $student['USUARIO AULA'];
            $string_status = $student['ESTADO'];
            $status = explode(" / ", $string_status);
            $avalibles = [];
            $statu = $status[0];
            $space = strpos($statu, ' ');

            if ($space !== null) {
                $statu = substr($statu, 0, $space);

                if ($statu == 'HABILITADO') {
                    $avalibles = explode(" ", $status[0]);
                }
            } else {
                $status = $status[0];
            }

            $student_data = [];
            $student_data['name'] = $nombreCompletoCliente;
            $student_data['phone'] = $telefono;
            $student_data['email'] = $correo;
            $student_data['classroom_user'] = $usuario_aula;
            $student_data['document'] = '';
            $student_id = self::create($student_data);

            $cadena_sap = $student['SAP'];
            $saps = explode(" + ", $cadena_sap);
            $cursos = [
                'EXCEL' => 'EXCEL',
                'PBI' => 'POWERBI BASICO',
                'MS PROJECT' => 'MS PROJECT',
            ];

            foreach ($cursos as $clave => $nombreCurso) {
                if ($student[$clave] != 'NO APLICA') {
                    $course_id = CoursesController::searchCourse($nombreCurso);
                    StudentCoursesController::create($student_id, $course_id);
                }
            }

            foreach ($saps as $sap) {
                $name_sap = substr($sap, 4);

                if (count(array_intersect([$name_sap], $avalibles)) > 0) {
                    $course_id = CoursesController::searchCourse($sap);
                    StudentCoursesController::create($student_id, $course_id);
                }
            }
        }
        return response()->json('Datos cargados');
    }

    public function student()
    {
        $students = Student::select('name', 'email', 'phone', 'id')->with('courses:short_name')->get();
        return response()->json($students);
    }

    public function getStudentDetails($id)
    {

        $student = Student::find($id);

        if (!$student) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        return response()->json($student);
    }

    public function editStudent(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);
        $student = Student::find($request->id);
        if (!$student) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Estudiante actualizado correctamente']);
    }

    public function deleteStudent(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:students,id',
        ]);
        $student = Student::find($request->id);
        if (!$student) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }
        $studentCourses = StudentCourses::where('student_id', $student->id)->get();
        foreach ($studentCourses as $studentCourse) {
            $studentCourse->delete();
        }
        $student->delete();

        return response()->json(['message' => 'Estudiante y registros relacionados eliminados correctamente']);
    }
}
