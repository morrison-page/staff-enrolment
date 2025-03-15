<?php
require './headers.php';
require '../classes/Router.php';
require '../controllers/CoursesController.php';
require '../controllers/UsersController.php';
require '../controllers/EnrolmentsController.php';

use Backend\Controllers\CoursesController;
use Backend\Controllers\UsersController;
use Backend\Controllers\EnrolmentsController;
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
$router->addRoute('GET', '/enrolments/{id}', [new EnrolmentsController(), 'show']);
$router->addRoute('POST', '/enrolments', [new EnrolmentsController(), 'create']);
$router->addRoute('PUT', '/enrolments/{id}', [new EnrolmentsController(), 'update']);
$router->addRoute('DELETE', '/enrolments/{id}', [new EnrolmentsController(), 'delete']);

$router->dispatch();

?>