<?php include_once __DIR__ . '/header-dash.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../template/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar contraseña</a>

    <form class="formulario" method="POST">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu nombre"
            value="<?php echo $usuario->nombre; ?>"
            />
        </div>
        <div class="campo">
            <label for="email">Correo</label>
            <input 
            type="text"
            id="email"
            name="email"
            placeholder="Tu Correo"
            value="<?php echo $usuario->email; ?>"
            />
        </div>
        <input type="submit" value="Guardar cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dash.php'; ?>