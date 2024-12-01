<?php

namespace Controller;
use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController{
    public static function login(Router $router){
     $alertas = [];
     $auth = new Usuario();
      if($_SERVER['REQUEST_METHOD'] === "POST"){
        $auth = new Usuario($_POST);
        $alertas=$auth->validarLogin();

        if(empty($alertas)){
            $usuario = Usuario::where("email", $auth->email);
            if($usuario){
                $validado=$usuario->validarPasswordAndValidado($auth->password);
                if($validado){
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    if($usuario->admin == 1){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');
                    }else {
                        header('Location: /cita');
                    }
                }

            }else{
                Usuario::setAlerta("error", "Email no es correcto");
            }
        }
      }
      $alertas = Usuario::getAlertas();
      $router ->render('auth/login', [
        "alertas"=> $alertas,
        "auth" => $auth
      ]);
    }


    public static function logout(){
       session_start();
       
       $_SESSION= [];

       header('Location: /');
    }


    public static function olvide(Router $router){
    $alertas = [];
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $auth = new Usuario($_POST);
        $alertas=$auth->validarEmail();

        if(empty($alertas)){
            $usuario = Usuario::where("email", $auth->email);
            if($usuario && $usuario->confirmado == 1){
                $usuario->crearToken();
                $usuario->guardar();
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarInstrucciones();
                $usuario::setAlerta("exito", "Revisa tu email");
            }else {
                Usuario::setAlerta("error", "El usuario no existe o no esta confirmado");
            }
        }
    }
       $alertas = Usuario::getAlertas();
       $router->render('auth/olvide',[
        "alertas" => $alertas,
       ]);
    }


    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
      
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta("error", "Token no valido");
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado =$usuario->guardar();
                if($resultado){
                    header("Location: /" );
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar', [
            "alertas" => $alertas,
            "error" => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          $usuario->sincronizar($_POST);
          $alertas = $usuario->validarNuevaCuenta();

          if(empty($alertas)){
                $resultado=$usuario->existeUsuario();
                if($resultado->num_rows > 0){
                    $alertas = Usuario::getAlertas();
                }else{
                    $usuario->hashPassword();

                    $usuario->crearToken();

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();


                    $resultado = $usuario->guardar();

                    if($resultado){
                       header('Location: mensaje');
                    }
                }
          }
        
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $usuario=Usuario::where('token', $token);
       
        if(empty($usuario)){
            Usuario::setAlerta("error", "Confirmacion Invalida");
        }else {
            Usuario::setAlerta("exito", "Confirmacion Valida");
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
        }
      
        $alertas = Usuario::getAlertas();
       
        $router->render('auth/confirmar-cuenta',[
            "alertas"=> $alertas
        ]);
    }


    
}