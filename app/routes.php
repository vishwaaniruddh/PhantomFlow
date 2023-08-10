<?php 
// $router->define([
//     ''=>'controllers/index.php',
//     'index'=>'controllers/index.php',
//     'about'=>'controllers/about.php',

// ]);


$router->get('','PagesController@home');
$router->get('index','PagesController@home');
$router->get('about','PagesController@about');

$router->get('users','UsersController@index');
$router->post('users','UsersController@store');
// $router->post('saveform','controllers/saveName.php');


// var_dump($router->routes);
?>