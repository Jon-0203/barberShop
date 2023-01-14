<?php

namespace Controllers;

use Model\Servicio;

class ApiController {
    public static function index() {
        $servicios = Servicio::all();//este metodo esta en Actrecord y nos instancia todos los datos de la DB
        echo json_encode($servicios);
    }
}