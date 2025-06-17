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

// admin
$routes->get('admin/', 'Admin\Main::index', $auth_filters);

// admin user
$routes->get('admin/users/', 'Admin\User\Main::index', $auth_filters);

// admin class
$routes->get('admin/classes/', 'Admin\Classes\Main::index', $auth_filters);

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
$routes->get('teacher/attendance/subject/', 'Teacher\Attendance\Main::index', $auth_filters);

// teacher attendance report
$routes->get('teacher/report/attendance/', 'Teacher\Attendance\Report\Main::index', $auth_filters);
$routes->get('teacher/report/attendance-subject/', 'Teacher\Attendance\Report\Subject::index', $auth_filters);

