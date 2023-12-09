<?php

namespace App\LDAP\controller;


use App\LDAP\controller\AbstractController;

class ControllerDefault extends AbstractController{


    public static function chooseDirectory(){
        self::afficheVue("view.php", ["Pagetitle" => "Local or IUT", "cheminVueBody"=>"directorySelection.php"]);
    }

    public static function notFound(){
        self::afficheVue("NotFound.php");
    }
}