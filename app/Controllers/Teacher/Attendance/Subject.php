<?php

namespace App\Controllers\Teacher\Attendance;

use App\Controllers\BaseController;
use App\Models\ClassTimetablePeriodModel;
use App\Models\TeacherClassSemesterSubjectModel;
use App\Models\TeacherModel;
use CodeIgniter\HTTP\ResponseInterface;

class Subject extends BaseController
{
    public function index()
    {
        $profileId = session()->get('user')['profile_id'];
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->getDataByProfileId($profileId);

        $tcssModel = new TeacherClassSemesterSubjectModel();
        $teacher_class_semester_subjects = $tcssModel-> getInSessionTcssByTeacher($teacher['id']);
        
        $cssIds = array_column($teacher_class_semester_subjects, 'css_id');

        $ctpModel = new ClassTimetablePeriodModel();
        $ctpList = $ctpModel-> getActiveByCssIds($cssIds);
        dd($cssIds, $ctpList);
        return view('teacher/attendance/subject/index', [
            'teacher' => $teacher,
            'teacher_class_semester_subjects' => $teacher_class_semester_subjects,
            'viewing' => 'attendance-subject'
        ]);
    }

    public function class_attendance(){
        // class semester subject id

        return view('teacher/attendance/subject/class_attendance', [
            'viewing' => 'attendance-subject'
        ]);
    }

    public function subject_attendance($id, $day, $month, $year){
        // class semester subject id
    }
}
