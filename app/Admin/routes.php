<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('articles', ArticleController::class);
    $router->resource('category', CategoryController::class);
    $router->resource('authors', AuthorController::class);
    $router->resource('contenttype', ContentController::class);
    $router->resource('module', ModuleController::class);
});
