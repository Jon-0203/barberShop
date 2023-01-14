<h1 class="titulo-pagina">¿Olvidaste tu password?</h1>
<p class="descripcion-pagina">Ingresa tu dirección de correo electronico</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">E-mail</label>
        <input 
            type="email"
            id="email"
            placeholder="Email"
            name="email"
        />
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="opciones">
    <a href="/">Ya tienes cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes cuenta? Crea una</a>
</div>