<?php

class Router{

    public $routes = [
        'GET'=>[],
        'POST'=>[]
    ];
    public function define($routes){
        $this->routes = $routes ; 
    }

    public function get($uri,$controller){
       return $this->routes['GET'][$uri] = $controller ; 
    }
    public function post($uri,$controller){
        return $this->routes['POST'][$uri] = $controller ; 
    }
    public function direct($uri,$requestType){
        
        if(array_key_exists($uri,$this->routes[$requestType])){
            return $this->callAction(
                ...explode('@',$this->routes[$requestType][$uri])
            );
        
        }else{
            echo  ('No Routes Define');
             
        }
    }

    protected function callAction($controller,$action){
         
        $controller = new $controller ; 
        if(! method_exists($controller,$action)){
            throw new Exception(
            "{$controller} doesnt have the method {$action}."
            );
        }
        return $controller->$action();

    }
}
?>