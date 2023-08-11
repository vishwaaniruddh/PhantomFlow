<?php session_start(); 
    
    error_reporting(0);
    require_once 'vendor/autoload.php';
    require_once 'core/bootstrap.php';

    $router = new Router ; 
    require 'app/routes.php';

    $router->direct(Request::uri(),Request::method());


?>