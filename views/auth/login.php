<h1 class="titulo-pagina">Login</h1>
<p class="descripcion-pagina">Inicio de sesión</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Email"
            name="email"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Iniciar sesión">
</form>

<div class="opciones">
    <a href="/crear-cuenta">¿No tienes cuenta? Registrate</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>