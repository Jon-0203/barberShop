<?php
    foreach($alertas as $llaves => $mensajes):
        foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo $llaves; ?>">
            <?php echo $mensaje; ?>
    </div>
<?php
    endforeach;
    endforeach;
?>