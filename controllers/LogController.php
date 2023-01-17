<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LogController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                //comprobar si el usuario existe
                $usuario = Usuario::WHERE('email', $auth->email);

                if ($usuario) {
                    //Comprobar el password
                    if ($usuario->comprobarPasswordandVerificar($auth->password)) {
                        //Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento

                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no registrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        //render nos permite tomar una vista y nos deja pasarle los datos
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::WHERE('email', $auth->email);

                if ($usuario && $usuario->confirmado === "1") {

                    //Generar un token

                    $usuario->crearToken();
                    $usuario->guardar();

                    // TODO : Enviar el email

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('ok', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'Usuario no existe o cuenta no confirmada');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        //Buscar usuario por el token
        $usuario = Usuario::WHERE('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {

                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();

                $usuario->token = null;
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('location: /');
                }
            }
        }    
            //Obtener alertas
            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar-password', [
                'alertas' => $alertas,
                'error' => $error
            ]);
        
    }
    public static function crear(Router $router)
    {
        $usuario = new Usuario;
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //debuguear($alertas);

            //Revisar que alertas esta vacio
            if (empty($alertas)) {
                //echo "Validación correcta";
                //verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear password
                    $usuario->hashPassword();

                    $usuario->crearToken();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        //echo "Guardado correctamente";
                        header('Location: /mensaje');
                    }

                    //debuguear($usuario);
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::WHERE('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'token no válido');
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = '1';
            $usuario->token      = "";
            $usuario->guardar();
            Usuario::setAlerta('ok', 'Cuenta comprobada correctamente');
        }
        //Obtener alertas
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
