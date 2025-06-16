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


// teacher
$routes->get('teacher/', 'Teacher\Main::index', $auth_filters);
$routes->get('teacher/attendance/', 'Teacher\Attendance\Main::index', $auth_filters);
$routes->get('teacher/attendance/subject/', 'Teacher\Attendance\Main::index', $auth_filters);

// teacher attendance report
$routes->get('teacher/attendance/report/', 'Teacher\Attendance\Report\Main::index', $auth_filters);
$routes->get('teacher/attendance/report/subject/', 'Teacher\Attendance\Report\Subject::index', $auth_filters);

