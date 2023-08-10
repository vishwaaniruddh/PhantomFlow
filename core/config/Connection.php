<?php
class Connection{
    public static function make(){

        try{
            return new PDO('mysql:host=localhost;dbname=sarmicrosystems_advantage','root','');
        }catch(PDOException $e){
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}