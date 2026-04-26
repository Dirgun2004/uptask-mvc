<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu Nueva Contraseña</p>
        <form method="post" class="formulario">
            <?php include_once __DIR__ . '/../template/alertas.php';
            if($error) :
            ?>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                type="password"
                name="password"
                id="password"
                placeholder="Tu Contraseña"
                />
            </div>
            <input type="submit" value="Actualizar Contraseña" class="boton">
            <?php endif; ?>
        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes cuenta?</a>
            <a href="/olvide">¿Tienes cuenta? Iniciar Sesion</a>
        </div>
    </div>
</div>