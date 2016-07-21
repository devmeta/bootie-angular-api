<?php 

use \Bootie\App as App;

App::route('/',                       ['uses' => 'Controller\ApiController@index']);
App::route('/v1/landing',             ['uses' => 'Controller\HomeController@index']);
App::route('/v1/logout',              ['uses' => 'Controller\AuthController@logout']);
App::route('/v1/login',               ['uses' => 'Controller\AuthController@login',   'method' => 'post']);
App::route('/v1/signup',              ['uses' => 'Controller\AuthController@signup',  'method' => 'post']);
App::route('/v1/packs',               ['uses' => 'Controller\PackController@index']);
App::route('/v1/packs/([^/]+)',       ['uses' => 'Controller\PackController@show']);
App::route('/v1/blog/([^/]+)',        ['uses' => 'Controller\BlogController@show']);
App::route('/v1/files/(\w+)/(\d+)',   ['uses' => 'Controller\FileController@index']);
App::route('/v1/files/resize',        ['uses' => 'Controller\FileController@resize',  'before' => 'auth.admin','method' => 'post']);
App::route('/v1/files/upload',        ['uses' => 'Controller\FileController@upload',  'before' => 'auth.admin','method' => 'post']);
App::route('/v1/files/order',         ['uses' => 'Controller\FileController@order',   'before' => 'auth.admin','method' => 'post']);
App::route('/v1/files/remove',        ['uses' => 'Controller\FileController@destroy', 'before' => 'auth.admin','method' => 'post']);
App::route('/v1/tags/remove/(\d+)',   ['uses' => 'Controller\TagController@remove',   'before' => 'auth.admin','method' => 'post']);
App::route('/v1/tags/add/(\w+)/(\d+)',['uses' => 'Controller\TagController@add',      'before' => 'auth.admin','method' => 'post']);
App::route('/v1/tags/(\w+)/(\d+)',    ['uses' => 'Controller\TagController@tags',     'before' => 'auth.admin']);
App::resource('/v1/admin/posts',      ['uses' => 'Controller\Admin\PostController',         'before' => 'auth.admin']);
App::resource('/v1/admin/users',      ['uses' => 'Controller\Admin\UserController',         'before' => 'auth.admin']);
App::resource('/v1/admin/packs',      ['uses' => 'Controller\Admin\PackController',         'before' => 'auth.admin']);

App::filter('auth.admin',function(){
    \Controller\AuthController::parseAuthRequest(['admin']);
});