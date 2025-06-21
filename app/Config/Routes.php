<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$auth_filters = [
    'filter' => 'auth'
];

// $routes->get('/', 'Home::index');

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
$routes->get('admin/users/', 'Admin\User\Main::index', $auth_filters);
$routes->post('admin/users/check-username/', 'Admin\User\Util::check_username', $auth_filters);
$routes->get('admin/users/(:num)/', 'Admin\User\Main::users/$1', $auth_filters);
$routes->get('admin/users/(:num)/create/', 'Admin\User\Main::create/$1', $auth_filters);
$routes->post('admin/users/(:num)/create/', 'Admin\User\Main::create/$1', $auth_filters);
$routes->get('admin/users/(:num)/edit/(:num)/user/', 'Admin\User\Main::edit_user/$1/$2', $auth_filters);
$routes->post('admin/users/(:num)/edit/(:num)/user/', 'Admin\User\Main::edit_user/$1/$2', $auth_filters);
$routes->get('admin/users/(:num)/edit/(:num)/profile/', 'Admin\User\Main::edit_profile/$1/$2', $auth_filters);
$routes->post('admin/users/(:num)/edit/(:num)/profile/', 'Admin\User\Main::edit_profile/$1/$2', $auth_filters);
$routes->get('admin/users/(:num)/edit/(:num)/additional/', 'Admin\User\Main::edit_additional/$1/$2', $auth_filters);
$routes->post('admin/users/(:num)/edit/(:num)/additional/', 'Admin\User\Main::edit_additional/$1/$2', $auth_filters);

// academic year
$routes->get('admin/academic-year/', 'Admin\AcademicYear\Main::index', $auth_filters);
$routes->get('admin/academic-year/create/', 'Admin\AcademicYear\Main::create', $auth_filters);
$routes->post('admin/academic-year/create/', 'Admin\AcademicYear\Main::create', $auth_filters);
$routes->get('admin/academic-year/(:num)/', 'Admin\AcademicYear\Main::detail/$1', $auth_filters);
$routes->get('admin/academic-year/(:num)/edit/', 'Admin\AcademicYear\Main::edit/$1', $auth_filters);
$routes->post('admin/academic-year/(:num)/edit/', 'Admin\AcademicYear\Main::edit/$1', $auth_filters);
$routes->get('admin/academic-year/(:num)/semester/edit/', 'Admin\AcademicYear\semester::edit/$1', $auth_filters);
$routes->post('admin/academic-year/(:num)/semester/edit/', 'Admin\AcademicYear\semester::edit/$1', $auth_filters);
$routes->get('cron/auto-alfa', 'Cron::autoAlfa');

// admin class
$routes->group('admin/classes', function($routes) {
    // Pilih Academic Year → auto redirect pakai GET param
    $routes->get('/', 'Admin\Classes\Main::index');
    $routes->get('academic-year/(:num)/', 'Admin\Classes\Main::class_academic_year/$1');
    
    $routes->get('academic-year/(:num)/semester/(:num)/class/', 'Admin\Classes\ClassSemester::index/$1/$2');
    $routes->get('academic-year/(:num)/semester/(:num)/class/create/', 'Admin\Classes\ClassSemester::create/$1/$2');
    $routes->post('academic-year/(:num)/semester/(:num)/class/create/', 'Admin\Classes\ClassSemester::create/$1/$2');
    $routes->get('academic-year/(:num)/semester/(:num)/class/(:num)/', 'Admin\Classes\ClassSemester::detail/$1/$2/$3');
    $routes->get('academic-year/(:num)/semester/(:num)/class/(:num)/edit/', 'Admin\Classes\ClassSemester::edit/$1/$2/$3');
    $routes->post('academic-year/(:num)/semester/(:num)/class/(:num)/edit/', 'Admin\Classes\ClassSemester::edit/$1/$2/$3');
    
    // class student
    $routes->get('academic-year/(:num)/semester/(:num)/class/(:num)/students/', 'Admin\Classes\ClassSemester::students/$1/$2/$3');
    $routes->post('academic-year/(:num)/semester/(:num)/class/(:num)/students/', 'Admin\Classes\ClassSemester::students/$1/$2/$3');
    $routes->get('academic-year/(:num)/semester/(:num)/class/(:num)/students/(:num)/delete/', 'Admin\Classes\ClassSemester::delete/$1/$2/$3/$4');

    $routes->get('academic-year/(:num)/semester/(:num)/class/(:num)/class-hour/', 'Admin\Classes\ClassSemester::class_hour/$1/$2/$3');
    $routes->post('academic-year/(:num)/semester/(:num)/class/(:num)/class-hour/', 'Admin\Classes\ClassSemester::class_hour/$1/$2/$3');
});

// admin subject
$routes->get('admin/subject/', 'Admin\Subject\Main::index', $auth_filters);
$routes->get('admin/subject/class/', 'Admin\Subject\Classes::index', $auth_filters);
$routes->get('admin/subject/teacher/', 'Admin\Subject\Teacher::index', $auth_filters);

// admin report attendance
$routes->get('admin/report/attendance/', 'Admin\Report\Main::index', $auth_filters);
$routes->get('admin/report/attendance-subject/', 'Admin\Report\Subject::index', $auth_filters);


// teacher
$routes->get('teacher/', 'Teacher\Main::index', $auth_filters);
$routes->get('teacher/attendance/', 'Teacher\Attendance\Main::index', $auth_filters);
$routes->post('teacher/attendance/', 'Teacher\Attendance\Main::index', $auth_filters);
$routes->get('teacher/attendance/subject/', 'Teacher\Attendance\Subject::index', $auth_filters);

// teacher attendance report
$routes->get('teacher/report/attendance/', 'Teacher\Report\Main::index', $auth_filters);
$routes->get('teacher/report/attendance/(:num)/download/', 'Teacher\Report\Main::exportData/$1', $auth_filters);
$routes->get('teacher/report/attendance-subject/', 'Teacher\Report\Subject::index', $auth_filters);

