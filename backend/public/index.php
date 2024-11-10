<?php
require './headers.php';
require '../classes/Router.php';
require '../controllers/CoursesController.php';
require '../controllers/UsersController.php';
require '../controllers/EnrollmentsController.php';

use Backend\Controllers\CoursesController;
use Backend\Controllers\UsersController;
use Backend\Controllers\EnrollmentsController;
use Backend\Router;

// Backend Entry Point

// Define Routes
$router = new Router('/apis');

// // Users
// $router->addRoute('GET', '/users', [new UsersController(), 'index']);
// $router->addRoute('GET', '/users/{id}', [new UsersController(), 'show']);
// $router->addRoute('POST', '/users', [new UsersController(), 'create']);
// $router->addRoute('PUT', '/users/{id}', [new UsersController(), 'update']);
// $router->addRoute('DELETE', '/users/{id}', [new UsersController(), 'delete']);

// Courses
$router->addRoute('GET', '/courses', [new CoursesController(), 'index']);
$router->addRoute('GET', '/courses/{id}', [new CoursesController(), 'show']);
$router->addRoute('POST', '/courses', [new CoursesController(), 'create']);
$router->addRoute('PUT', '/courses/{id}', [new CoursesController(), 'update']);
$router->addRoute('DELETE', '/courses/{id}', [new CoursesController(), 'delete']);

// // Enrollments
// $router->addRoute('GET', '/enrollments', [new EnrollmentsController(), 'index']);
// $router->addRoute('GET', '/enrollments/{id}', [new EnrollmentsController(), 'show']);
// $router->addRoute('POST', '/enrollments', [new EnrollmentsController(), 'create']);
// $router->addRoute('PUT', '/enrollments/{id}', [new EnrollmentsController(), 'update']);
// $router->addRoute('DELETE', '/enrollments/{id}', [new EnrollmentsController(), 'delete']);

$router->dispatch();

?>