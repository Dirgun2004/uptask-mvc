<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>
        <form method="post" class="formulario">
            <?php include_once __DIR__ . '/../template/alertas.php' ?>
            <div class="campo">
                <label for="email">Correo</label>
                <input 
                type="email"
                name="email"
                id="email"
                placeholder="Tu Correo"
                />
            </div>

            <input type="submit" value="Enviar Instrucciones" class="boton">
        </form>
        <div class="acciones">
            <a href="/">Ya tengo cuenta</a>
            <a href="/crear">¿No tienes cuenta?</a>
        </div>
    </div>
</div>