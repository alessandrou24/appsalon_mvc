<?php

namespace Controller;
use Model\Cita;
use Model\Servicio;
use Model\CitaServicio;
use Model\Usuario;
use MVC\Router;
use Classes\Email;

class ApiController{

    public static function index(Router $router){
        $servicios = Servicio::all();   

       echo json_encode($servicios);

       
    }

    public static function guardar(Router $router){
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args = ["citaId" => $resultado['id'], "servicioId" => $idServicio];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        $respuesta = [
            'resultado' => $resultado
        ];
        echo json_encode($resultado);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $id = $_POST['id'];

            $cita = Cita::find($id);
            $cita ->eliminar();
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}
