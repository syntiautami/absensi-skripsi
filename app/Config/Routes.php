<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$auth_filters = [
    'filter' => 'auth'
];

// $routes->get('/', 'Home::index');

$routes->get('cron/auto-alfa/', 'Cron::autoAlfa');


$routes->get('/', 'Auth::login');
$routes->post('/', 'Auth::attemptLogin');
$routes->get('role/', 'Auth::chooseRole', $auth_filters);
$routes->get('role/(:segment)/', 'Auth::setRole/$1', $auth_filters);
$routes->get('logout/', 'Auth::logout');

// attendance
$routes->get('attendance/', 'Attendance\Main::index', $auth_filters);
$routes->post('attendance/tapping/', 'Attendance\Main::tapping', $auth_filters);

// admin
$routes->get('admin/', 'Admin\Main::index', $auth_filters);

// admin user
$routes->group('admin/users', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\User\Main::index');
    $routes->post('check/(:any)/', 'Admin\User\Util::user_check/$1');
    $routes->get('(:num)/', 'Admin\User\Main::users/$1');
    
    $routes->match(['get', 'post'], '(:num)/create/', 'Admin\User\Main::create/$1');

    $routes->match(['get', 'post'], '(:num)/delete/(:num)/', 'Admin\User\Main::delete_user/$1/$2');
    $routes->match(['get', 'post'], '(:num)/edit/(:num)/user/', 'Admin\User\Main::edit_user/$1/$2');
    $routes->match(['get', 'post'], '(:num)/edit/(:num)/profile/', 'Admin\User\Main::edit_profile/$1/$2');
    $routes->match(['get', 'post'], '(:num)/edit/(:num)/additional/', 'Admin\User\Main::edit_additional/$1/$2');
});

// admin academic year
$routes->group('admin/academic-year', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\AcademicYear\Main::index');
    $routes->match(['get', 'post'], 'create/', 'Admin\AcademicYear\Main::create');
    $routes->get('(:num)/', 'Admin\AcademicYear\Main::detail/$1');
    $routes->match(['get', 'post'], '(:num)/edit/', 'Admin\AcademicYear\Main::edit/$1');
    $routes->match(['get', 'post'], '(:num)/semester/edit/', 'Admin\AcademicYear\Semester::edit/$1');
});

// admin class
$routes->group('admin/classes', ['filter' => 'auth'], function($routes) {
    // Main
    $routes->get('/', 'Admin\Classes\Main::index');
    $routes->get('academic-year/(:num)/', 'Admin\Classes\Main::class_academic_year/$1');
    $routes->get('academic-year/(:num)/delete/(:num)/', 'Admin\Classes\Main::delete/$1/$2');
    
    $routes->match(['get','post'],'academic-year/(:num)/class-semester-year/create/', 'Admin\Classes\Main::create/$1');
    // Class Semester
    $routes->group('academic-year/(:num)/class-semester-year/(:num)/', function($routes) {
        $routes->get('/', 'Admin\Classes\ClassSemester::detail/$1/$2');
        $routes->match(['get', 'post'], 'create/', 'Admin\Classes\ClassSemester::create/$1/$2');
        $routes->match(['get', 'post'], 'edit/', 'Admin\Classes\ClassSemester::edit/$1/$2');

        // Students
        $routes->match(['get', 'post'], 'students/', 'Admin\Classes\ClassSemester::students/$1/$2');
        $routes->get('students/(:num)/delete/', 'Admin\Classes\ClassSemester::delete/$1/$2/$3');

        // Class Hour
        $routes->match(['get', 'post'], 'class-hour/', 'Admin\Classes\ClassSemester::class_hour/$1/$2');

        // Timetable
        $routes->get('timetable/', 'Admin\Classes\Timetable::index/$1/$2');
        $routes->get('timetable/(:num)/', 'Admin\Classes\Timetable::days/$1/$2/$3');
        $routes->match(['get', 'post'], 'timetable/(:num)/day/(:num)/', 'Admin\Classes\Timetable::class_timetable_period/$1/$2/$3/$4');
    });
});

// admin subject
$routes->group('admin/subject', ['filter' => 'auth'], function($routes) {
    // class
    $routes->get('/', 'Admin\Subject\Main::index');
    $routes->get('class/', 'Admin\Subject\Classes::index');
    $routes->get('class/academic-year/(:num)/', 'Admin\Subject\Classes::classes/$1');
    $routes->match(['get','post'],'class/academic-year/(:num)/class-semester-year/(:num)/', 'Admin\Subject\Classes::class_subjects/$1/$2');
    
    // teacher
    $routes->get('teacher/', 'Admin\Subject\Teacher::index');
    $routes->get('teacher/academic-year/(:num)/', 'Admin\Subject\Teacher::classes/$1');
    $routes->match(['get','post'], 'teacher/academic-year/(:num)/class-semester-year/(:num)/', 'Admin\Subject\Teacher::teacher_subjects/$1/$2');
});

// admin report attendance
$routes->group('admin/report', ['filter' => 'auth'], function($routes) {
    // attendance
    $routes->get('attendance/', 'Admin\Report\Main::index');
    $routes->get('attendance/academic-year/(:num)/', 'Admin\Report\Main::grades/$1');
    
    // download attendance
    $routes->get('attendance/(:num)/download/', 'Attendance\Report::attendance/$1');

    // attendance subject
    $routes->get('attendance/subject/', 'Admin\Report\Subject::index');
    $routes->get('attendance/subject/(:num)/', 'Admin\Report\Subject::classes/$1');
    $routes->get('attendance/subject/(:num)/class-semester-year/(:num)', 'Admin\Report\Subject::subject_list/$1/$2');
    
    // download attendance subject
    $routes->get('attendance/subject/(:num)/download', 'Attendance\Report::attendance_subject/$1');
});

// teacher
$routes->group('teacher', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Teacher\Main::index');
    
    // attendance
    $routes->match(['get', 'post'], 'attendance/', 'Teacher\Attendance\Main::index');
    
    // attendance subject
    $routes->get('attendance/subject/', 'Teacher\Attendance\Subject::index');
    $routes->match(['GET','POST'],'attendance/subject/(:num)/year/(:num)/month/(:num)/day/(:num)', 'Teacher\Attendance\Subject::class_subject_attendance/$1/$2/$3/$4');

    // laporan absensi
    $routes->get('report/attendance/', 'Teacher\Report\Main::index');
    $routes->get('report/attendance/(:num)/download/', 'Attendance\Report::attendance/$1');

    // laporan absensi subject
    $routes->get('report/attendance/subject/', 'Teacher\Report\Subject::index');
    $routes->get('report/attendance/subject/(:num)/', 'Teacher\Report\Subject::subjects/$1');
    $routes->get('report/attendance/subject/(:num)/download', 'Attendance\Report::attendance_subject/$1');
});
