<?php 
date_default_timezone_set('Asia/Kolkata');


$pdo = Connection::make();
$query = new QueryBuilder($pdo);

function view($name,$data=[]){
    extract($data);
    return require_once "app/views/{$name}.view.php";
}

function redirect($path){
    return header("Location: /{$path}");
}