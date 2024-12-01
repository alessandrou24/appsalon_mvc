<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Ingresa tu email para recibir instrucciones</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email">
    </div>
    <input type="submit" value="Recuperar" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
</div>