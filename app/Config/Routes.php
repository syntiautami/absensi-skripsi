<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$auth_filters = [
    'filter' => 'auth'
];

// $routes->get('/', 'Home::index');

$routes->get('cron/auto-alfa', 'Cron::autoAlfa');


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
    $routes->post('check-username/', 'Admin\User\Util::check_username');
    $routes->get('(:num)/', 'Admin\User\Main::users/$1');
    
    $routes->match(['get', 'post'], '(:num)/create/', 'Admin\User\Main::create/$1');

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

    // Class Semester
    $routes->group('academic-year/(:num)/semester/(:num)/class', function($routes) {
        $routes->get('/', 'Admin\Classes\ClassSemester::index/$1/$2');
        $routes->match(['get', 'post'], 'create/', 'Admin\Classes\ClassSemester::create/$1/$2');
        
        $routes->group('(:num)', function($routes) {
            $routes->get('/', 'Admin\Classes\ClassSemester::detail/$1/$2/$3');
            $routes->match(['get', 'post'], 'edit/', 'Admin\Classes\ClassSemester::edit/$1/$2/$3');

            // Students
            $routes->match(['get', 'post'], 'students/', 'Admin\Classes\ClassSemester::students/$1/$2/$3');
            $routes->get('students/(:num)/delete/', 'Admin\Classes\ClassSemester::delete/$1/$2/$3/$4');

            // Class Hour
            $routes->match(['get', 'post'], 'class-hour/', 'Admin\Classes\ClassSemester::class_hour/$1/$2/$3');

            // Timetable
            $routes->get('timetable/', 'Admin\Classes\Timetable::index/$1/$2/$3');
            $routes->match(['get', 'post'], 'timetable/(:num)/', 'Admin\Classes\Timetable::class_timetable_period/$1/$2/$3/$4');
        });
    });
});

// admin subject
$routes->group('admin/subject', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\Subject\Main::index');
    $routes->get('class/', 'Admin\Subject\Classes::index');
    $routes->get('teacher/', 'Admin\Subject\Teacher::index');
});

// admin report attendance
$routes->group('admin/report', ['filter' => 'auth'], function($routes) {
    $routes->get('attendance/', 'Admin\Report\Main::index');
    $routes->get('attendance-subject/', 'Admin\Report\Subject::index');
});

// teacher
$routes->group('teacher', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Teacher\Main::index');

    $routes->match(['get', 'post'], 'attendance/', 'Teacher\Attendance\Main::index');
    $routes->get('attendance/subject/', 'Teacher\Attendance\Subject::index');

    $routes->get('report/attendance/', 'Teacher\Report\Main::index');
    $routes->get('report/attendance/(:num)/download/', 'Teacher\Report\Main::exportData/$1');
    $routes->get('report/attendance-subject/', 'Teacher\Report\Subject::index');
});
