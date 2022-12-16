<?php

$db = mysqli_connect('localhost', 'root', '', '');


if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
