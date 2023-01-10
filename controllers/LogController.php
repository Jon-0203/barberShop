<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LogController{
    public static function login(Router $router) {
        $router->render('auth/login');//render nos permite tomar una vista y nos deja pasarle los datos
    }

    public static function logout() {
        echo "Desde Logout";
    }

    public static function olvide(Router $router) {
        $router->render('auth/olvide-password');
    }

    public static function recuperar() {
        echo "Desde recuperar";
    }

    public static function crear(Router $router) {
          $usuario = new Usuario;
          $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            //debuguear($alertas);

            //Revisar que alertas esta vacio
            if(empty($alertas)){
                //echo "Validación correcta";
                //verificar que el usuario no esye registrado
                $resultado = $usuario->existeUser();

                if($resultado -> num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear password
                    $usuario -> hashPassword();

                    $usuario -> crearToken();

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarConfirmacion();

                    debuguear($usuario);
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}