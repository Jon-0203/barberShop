<?php

namespace Model;

use LDAP\Result;

class Usuario extends ActiveRecord{

    //Base de datos

    protected static $tabla      = 'usuarios';
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

    public function __construct($args = [])
    {
        $this->id =$args['id'] ?? null;
        $this->nombre =$args['nombre'] ?? '';
        $this->apellido =$args['apellido'] ?? '';
        $this->email =$args['email'] ?? '';
        $this->password =$args['password'] ?? '';
        $this->telefono =$args['telefono'] ?? '';
        $this->admin =$args['admin'] ?? null;
        $this->confirmado =$args['confirmado'] ?? null;
        $this->token =$args['token'] ?? '';
    }

    public function validarNuevaCuenta(){
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido ss obligatorio ';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    //Revisa si existe el usuario
    public function existeUser() {
        
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado= self::$db->query($query);

        if($resultado -> num_rows){
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }
         return $resultado;
    }

    public function hashPassword(){
        $this ->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this -> token = uniqid();
    }
}