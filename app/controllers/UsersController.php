<?php

class UsersController{

    public function index(){
        $pdo = Connection::make();
        $query = new QueryBuilder($pdo);
        $result = $query->selectAll('users');

        return view('users',compact('result'));
    }
    public function store(){
        
        $pdo = Connection::make();
        $query = new QueryBuilder($pdo);
        
        $query->insert('users',[
            'name'=>$_POST['name']
        ]);
        return redirect('users');
    }
}