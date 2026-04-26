<div class="contenedor crear">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu Cuenta</p>
        <form method="post" class="formulario">
            <?php include_once __DIR__ . '/../template/alertas.php'; ?>
            <div class="campo">
                <label for="name">Nombre</label>
                <input 
                type="text"
                name="nombre"
                id="name"
                placeholder="Tu Correo"
                value="<?php echo $usuario->nombre ?? ''; ?>"
                />
            </div>
            <div class="campo">
                <label for="email">Correo</label>
                <input 
                type="email"
                name="email"
                id="email"
                placeholder="Tu Correo"
                value="<?php echo $usuario->email ?? ''; ?>"
                />
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                type="password"
                name="password"
                id="password"
                placeholder="Tu Contraseña"
                />
            </div>
            <div class="campo">
                <label for="password2">Repite tu Contraseña</label>
                <input 
                type="password"
                name="password2"
                id="password2"
                placeholder="Repite Tu Contraseña"
                />
            </div>
            <input type="submit" value="Iniciar Sesion" class="boton">
        </form>
        <div class="acciones">
            <a href="/">Ya tengo cuenta</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div>
</div>