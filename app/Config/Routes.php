<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication routes (case-insensitive)
$routes->get('/login', 'RfidController::index'); // RFID Scanner
$routes->get('/LOGIN', 'RfidController::index'); // RFID Scanner
$routes->get('/Login', 'RfidController::index'); // RFID Scanner
$routes->get('/admin-login', 'Auth::login'); // Admin login page
$routes->post('/authenticate', 'Auth::authenticate');
$routes->post('/AUTHENTICATE', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');
$routes->get('/LOGOUT', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/DASHBOARD', 'Auth::dashboard');

// Admin routes
$routes->get('/manage-users', 'ManageUsers::index');
$routes->post('/manage-users/add', 'ManageUsers::addUser');
$routes->post('/manage-users/check-school-id', 'ManageUsers::checkSchoolId');
$routes->post('/manage-users/edit', 'ManageUsers::editUser');
$routes->post('/manage-users/delete', 'ManageUsers::deleteUser');
$routes->get('/manage-users/get-all', 'ManageUsers::getAllUsers');
$routes->get('/profile', 'Auth::profile');

// Reports routes
$routes->get('/reports', 'Reports::index');
$routes->post('/reports/new-user-report', 'Reports::newUserReport');
$routes->post('/reports/attendance-report', 'Reports::attendanceReport');

// RFID Attendance routes
$routes->get('/rfid', 'RfidController::index');
$routes->post('/rfid/scan', 'RfidController::scan');
$routes->get('/rfid/attendance', 'RfidController::attendance');