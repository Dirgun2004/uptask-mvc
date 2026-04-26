<?php include_once __DIR__ . '/header-dash.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../template/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>

    <form class="formulario" method="POST">
        <div class="campo">
            <label for="password_old">Contraseña actual</label>
            <input 
            type="password"
            name="password_old"
            placeholder="Tu actual contraseña"
            />
        </div>
        <div class="campo">
            <label for="password_new">Contraseña Nueva</label>
            <input 
            type="password"
            name="password_new"
            placeholder="Tu nueva contraseña"
            />
        </div>
        <input type="submit" value="Guardar cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dash.php'; ?>