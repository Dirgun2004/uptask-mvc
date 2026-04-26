// build/js/proyecto.js
(function() {
    const botonesEliminar = document.querySelectorAll('.eliminar-proyecto');

    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function() {
            const proyectoId = boton.dataset.url; // Captura el data-url que pusiste en el HTML
            confirmarEliminarProyecto(proyectoId, boton.parentElement);
        });
    });

    function confirmarEliminarProyecto(id, elementoHTML) {
        Swal.fire({
            title: '¿Eliminar Proyecto?',
            text: "Esta acción también eliminará todas las tareas asociadas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarProyecto(id, elementoHTML);
            }
        })
    }

    async function eliminarProyecto(id, elementoHTML) {
        const datos = new FormData();
        datos.append('id', id);

        try {
            const url = '/eliminar-proyecto'; 
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if (resultado.tipo === 'exito') {
                Swal.fire('¡Eliminado!', 'El proyecto ha sido borrado.', 'success');

                elementoHTML.remove();
                
  
                const listado = document.querySelector('.listado-proyecto');
                if(listado.children.length === 0) {
                    location.reload();
                }
            }
        } catch (error) {
            console.log(error);
            Swal.fire('Error', 'Hubo un error al conectar con el servidor', 'error');
        }
    }
})();