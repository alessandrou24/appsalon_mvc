<h1 class="nombre-pagina">Recuperar tu contraseña</h1>
<p class="descripcion-pagina">Pon tu nueva contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return; ?>
<form  class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu nuevo password">
    </div>

    <input type="submit" class="boton" value="Cambiar">
</form>

<div class="acciones">
    <a href="/">Inicia Sesion</a>
    <a href="/crear-cuenta">Crea una cuenta</a>
</div>