<h1 class="titulo-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Ingresa tu nuevo password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return ?>
<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu nuevo Password aqui"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Guardar">
</form>

<div class="opciones">
    <a href="/">¿Ya tienes cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿No tienes cuenta? Registrate</a>
</div>