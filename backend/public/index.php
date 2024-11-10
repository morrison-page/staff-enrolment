<?php
require './headers.php';
require '../classes/Router.php';
require '../controllers/CoursesController.php';

use Backend\Controllers\CoursesController;
use Backend\Router;

// Backend Entry Point

// Define Routes
$router = new Router('/apis');

// Courses
$router->addRoute('GET', '/courses', [new CoursesController(), 'index']);
$router->addRoute('GET', '/courses/{id}', [new CoursesController(), 'show']);
$router->addRoute('POST', '/courses', [new CoursesController(), 'create']);
$router->addRoute('PUT', '/courses/{id}', [new CoursesController(), 'update']);
$router->addRoute('DELETE', '/courses/{id}', [new CoursesController(), 'delete']);

$router->dispatch();

?>