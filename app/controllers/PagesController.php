<?php

class PagesController{

    public function home(){
        // require_once 'views/index.view.php';
        return view('index');

    }
    public function about(){
        // require_once 'views/about.view.php';

        return view('about');

    }
}
?>