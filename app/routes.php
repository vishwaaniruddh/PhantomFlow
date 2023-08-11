<?php

// All GET ROUTES
$router->get('', 'PagesController@home');
$router->get('index', 'PagesController@home');
$router->get('about', 'PagesController@about');
$router->get('users', 'UsersController@index');

$router->get('add_mis', 'MisController@addMis');
$router->get('view_mis', 'MisController@viewMis');




// All POST ROUTES
$router->post('users', 'UsersController@store');

// POST routes for MIS
$router->post('get_atm_data', 'MisController@getAtmData');
$router->post('add_mis_comp_check', 'MisController@add_mis_comp_check');
$router->post('process_addMis', 'MisController@process_addMis');
$router->post('show_history', 'MisController@getHistory');
$router->post('get_mis', 'MisController@getMisRecords');


// Allow access to the login route without authentication
$router->get('login', 'UsersController@login');
$router->post('login', 'UsersController@login_process');

$router->get('logout', 'UsersController@logout');

// ... (rest of your route definitions)
?>
