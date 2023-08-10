<?php

class QueryBuilder{

    protected $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo ; 
    }
    public function selectAll($table){

        $statement = $this->pdo->prepare("select * from {$table}");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return $result ; 
    }

    public function insert($table,$parameters){
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ',array_keys($parameters)),
            ':' . implode(', :',array_keys($parameters))
        );

        try{
            $statement = $this->pdo->prepare($sql);
            $statement->execute($parameters);
        }catch(Exception $e){
            die('something wrong !');
        }
    }
}