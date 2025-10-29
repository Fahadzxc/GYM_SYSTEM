<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication routes
$routes->get('/login', 'Auth::login');
$routes->post('/authenticate', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');

// Admin routes
$routes->get('/manage-users', 'ManageUsers::index');
$routes->post('/manage-users/add', 'ManageUsers::addUser');
$routes->post('/manage-users/edit', 'ManageUsers::editUser');
$routes->post('/manage-users/delete', 'ManageUsers::deleteUser');
$routes->get('/manage-users/get-all', 'ManageUsers::getAllUsers');
$routes->get('/profile', 'Auth::profile');