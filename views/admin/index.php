<h1 class="titulo-pagina">Panel de administración</h1>

<?php
include_once __DIR__ . '/../templates/barra.php';
?>
<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" />
        </div>
    </form>
</div>

<div id="citas-admin">
    <ul class="citas">
        <?php
        $idCita = 0;
        foreach ($citas as $cita) {
            if ($idCita !== $cita->id) {
        ?>
                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Email: <span><?php echo $cita->email; ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>

                    <h3>Servicios</h3>
                <?php
                    $idCita = $cita->id;
            } // FIn del if ?>
                        <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>
                </li>
            <?php } //Fin de foreach ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>