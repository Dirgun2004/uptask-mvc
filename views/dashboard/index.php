<?php include_once __DIR__ . '/header-dash.php'; ?>

<?php if(count($proyectos) === 0){ ?>
    <p class="no-proyecto">Aún no has creado proyectos</p>
    <a href="/crear-proyecto" class="boton">Crea un Proyecto</a>
<?php } else { ?>
    <ul class="listado-proyecto">
        <?php foreach($proyectos as $proyecto) { ?>
            <li class="proyecto">
                <div class="eliminar-proyecto" data-url="<?php echo $proyecto->url; ?>">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Close_Circle"> <path class="icono-eliminar" id="Vector" d="M9 9L11.9999 11.9999M11.9999 11.9999L14.9999 14.9999M11.9999 11.9999L9 14.9999M11.9999 11.9999L14.9999 9M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                </div>
                <a href="/proyecto?id=<?php echo $proyecto->url; ?>"><?php echo $proyecto->proyecto; ?></a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
<?php include_once __DIR__ . '/footer-dash.php'; ?>
<?php $script = '
    <script src="build/js/proyecto.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

'; ?>
