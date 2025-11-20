<?php
require_once './libs/route.php';
require_once './controllers/item.controller.php';
require_once './controllers/category.controller.php';
require_once './models/item.model.php';

$router = new Router();

$router->addRoute('categorias',         'GET',      'CategoryController',    'getCategories');
$router->addRoute('categorias/:id',     'GET',      'CategoryController',    'getCategory');
$router->addRoute('categorias',         'POST',     'CategoryController',    'insertCategory');
$router->addRoute('categorias/:id',     'PUT',      'CategoryController',    'updateCategory');

$router->addRoute('prendas',         'GET',      'ItemController',    'getItems');
$router->addRoute('prendas/:id',     'GET',      'ItemController',    'getItem');
$router->addRoute('prendas/:id',     'PUT',      'ItemController',    'updateItem');
$router->addRoute('prendas',         'POST',     'ItemController',    'insertItem');


$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
