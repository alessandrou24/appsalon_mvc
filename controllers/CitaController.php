<?php

namespace Controller;
use Model\Usuario;
use MVC\Router;
use Classes\Email;

class CitaController{

    
   
    public static function index(Router $router){

        session_start();

        esAuth();
        $router->render('cita/index', [
            "nombre" =>$_SESSION['nombre'],
            "id" => $_SESSION['id']
        ]);    
    }
}