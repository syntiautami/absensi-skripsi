<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('login/', 'Auth::login');
$routes->post('login/', 'Auth::attemptLogin');
$routes->get('logout/', 'Auth::logout');

// attendance
$routes->get('attendance/', 'Attendance\Main::index', ['filter' => 'auth']);

// admin
$routes->get('admin/', 'Admin\Main::index', ['filter' => 'auth']);


// teacher
$routes->get('teacher/', 'Teacher\Main::index', ['filter' => 'auth']);
$routes->get('teacher/attendance/', 'Teacher\Attendance\Main::index', ['filter' => 'auth']);
$routes->get('teacher/attendance/subject/', 'Teacher\Attendance\Main::index', ['filter' => 'auth']);

// teacher attendance report
$routes->get('teacher/attendance/report/', 'Teacher\Attendance\Report\Main::index', ['filter' => 'auth']);
$routes->get('teacher/attendance/report/subject/', 'Teacher\Attendance\Report\Subject::index', ['filter' => 'auth']);

