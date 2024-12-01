<?php

namespace Model; 


class Usuario extends ActiveRecord{
    // Base de datos 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? "";
        $this->apellido = $args['apellido'] ?? "";
        $this->email = $args['email'] ?? "";
        $this->password = $args['password'] ?? "";
        $this->telefono = $args['telefono'] ?? "";
        $this->admin = $args['admin'] ?? 0; 
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? "";
    }
// Validacion de Usuarios
public function validarNuevaCuenta(){
    if($this->nombre === ""){
        self::$alertas['error'][] = "El usuario necesita un nombre";
    }
    if($this->apellido === ""){
        self::$alertas['error'][] = "El usuario necesita un apellido";
    }
    if($this->email === ""){
        self::$alertas['error'][] = "El usuario necesita un email";
    }
    
    if($this->password === ""){
        self::$alertas['error'][] = "El usuario necesita un password";
    }
    if(strlen($this->password) <6){
        self::$alertas['error'][] = "Password muy corto";
    }


    return self::$alertas;
}

public function existeUsuario(){
    $query = "SELECT * FROM " . self::$tabla .  " WHERE email = '" . $this->email . "' LIMIT 1"; 

    $resultado =  self::$db->query($query);

    if($resultado->num_rows > 0){
        self::$alertas['error'][] = "El usuario ya existe";
    }

    return $resultado;
}

public function hashPassword(){
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
}

public function crearToken(){
    $this->token = uniqid();
}

public function validarLogin(){
    if(!$this->email){
        self::$alertas['error'][] = "El email es obligatorio";
    }
    if(!$this->password){
        self::$alertas['error'][] = "El password es obligatorio";
    }
    return self::$alertas;
}

public function validarPasswordAndValidado($password){
    $resultado = password_verify($password, $this->password);
    if(!$resultado || $this->confirmado == 0 ){
       self::$alertas['error'][] = "El usuario esta mal";
    }else {
       return true;
    }
}

public function validarEmail(){
    if(!$this->email){
    self::$alertas['error'][] = "El email es obligatorio";
   
    }
    return self::$alertas;
}

public function validarPassword(){
    if(!$this->password) self::$alertas['error'][] = "El password es obligatorio";
    else if(strlen($this->password) < 6) self::$alertas['error'][] = "Password muy corto";
    return self::$alertas;
}
}