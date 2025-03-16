<?php
require __DIR__ . '/headers.php';
require __DIR__ . '/../classes/Router.php';
require __DIR__ . '/../controllers/CoursesController.php';
require __DIR__ . '/../controllers/UsersController.php';
require __DIR__ . '/../controllers/EnrolmentsController.php';
require __DIR__ . '/../controllers/AuthController.php';

use Backend\Controllers\CoursesController;
use Backend\Controllers\UsersController;
use Backend\Controllers\EnrolmentsController;
use Backend\Controllers\AuthController;
use Backend\Classes\Router;

// Backend Entry Point

// Define Routes
$router = new Router('/apis');

// Users
$router->addRoute('GET', '/users', [new UsersController(), 'index']);
$router->addRoute('GET', '/users/{id}', [new UsersController(), 'show']);
$router->addRoute('GET', '/users/{id}/courses', [new UsersController(), 'courses']);
$router->addRoute('POST', '/users', [new UsersController(), 'create']);
$router->addRoute('PUT', '/users/{id}', [new UsersController(), 'update']);
$router->addRoute('DELETE', '/users/{id}', [new UsersController(), 'delete']);

// Courses
$router->addRoute('GET', '/courses', [new CoursesController(), 'index']);
$router->addRoute('GET', '/courses/{id}', [new CoursesController(), 'show']);
$router->addRoute('POST', '/courses', [new CoursesController(), 'create']);
$router->addRoute('PUT', '/courses/{id}', [new CoursesController(), 'update']);
$router->addRoute('DELETE', '/courses/{id}', [new CoursesController(), 'delete']);

// Enrolments
$router->addRoute('GET', '/enrolments', [new EnrolmentsController(), 'index']);
$router->addRoute('POST', '/enrolments', [new EnrolmentsController(), 'create']);
$router->addRoute('DELETE', '/enrolments', [new EnrolmentsController(), 'delete']);

// Authentication
$router->addRoute('POST', '/auth', [new AuthController(), 'login']);
$router->addRoute('DELETE', '/auth', [new AuthController(), 'logout']);
$router->addRoute('GET', '/auth', [new AuthController(), 'user']);

$router->dispatch();

?>