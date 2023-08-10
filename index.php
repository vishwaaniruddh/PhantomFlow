<?php 
    require_once 'vendor/autoload.php';
    require_once 'core/bootstrap.php';

    // $query = new QueryBuilder($pdo);
    // $result = $query->selectAll('vendor');
    // var_dump($result);

    $router = new Router ; 

    require 'app/routes.php';

    $router->direct(Request::uri(),Request::method());
?>