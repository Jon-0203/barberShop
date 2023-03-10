<?php
 
namespace Controllers;
 
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
 
class APIController{
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }
 
    public static function guardar() {
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
 
        $id = $resultado['id'];
 
        // Almacena las Citas y el Servicio con el ID de la Cita
            // Split de PHP
        $idServicios = explode(",", $_POST['servicios']);
 
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        // Retornamos una Respuesta
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
        
    }
}