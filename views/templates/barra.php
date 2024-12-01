<div class="barra"> 
    <p>Hola: <?php echo $nombre ?? ""; ?></p>
    <a href="/logout" class="boton">Cerrar Sesion</a>

</div>

<?php if(isset($_SESSION['admin'])): ?>
        <div class="barra-servicios">
            <a class="boton" href="/admin">Ver citas</a>
            <a href="/servicios" class="boton">Ver servicios</a>
            <a href="/servicios/crear" class="boton">Crear servicio</a>
        </div>
    <?php endif; ?>