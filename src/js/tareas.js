(function(){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    const btnNuevaTarea = document.querySelector('.agregar-tarea');
    btnNuevaTarea.addEventListener('click', function(){
        mostrarformulario();
    });

    // FILTROS DE BUSQUEDA
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e){
        const filtro = e.target.value;

        if(filtro !== ''){
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        }else{
            filtradas = [];
        }
    
        mostrarTarea();
    }

    async function obtenerTareas(){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTarea();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTarea(){
        limpiarTareas();

        totalPendientes();
        totalCompletas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.classList.add('no-tareas');
            textoNoTareas.textContent = 'No hay tareas';

            contenedorTareas.appendChild(textoNoTareas);

            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        };

        arrayTareas.forEach(tarea =>{
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            // Titulo de Tarea
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarformulario(true, {...tarea});
            }

            // Div opciones
            const opcionesDiv =document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function(){
                cambiarEstadoBoton({...tarea});
            };

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = function(){
                botonEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
    }

    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const btnPendientes = document.querySelector('#pendientes');
        if(totalPendientes.length === 0){
            btnPendientes.disabled = true;
        }else{
            btnPendientes.disabled = false;
        }
    }
    
    function totalCompletas(){
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const btnCompletas = document.querySelector('#completadas');
        if(totalCompletas.length === 0){
            btnCompletas.disabled = true;
        }else{
            btnCompletas.disabled = false;
        }
    }

    function mostrarformulario(editar = false, tarea){
        
        const modal = document.createElement('DIV');
        modal.classList.add('modal');

        modal.innerHTML = `
        
            <form class="formulario tarea-nueva" >
                <legend>${editar ? 'Edita tu tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                    type="text"
                    name="tarea"
                    id="tarea"
                    placeholder="${editar ? 'Edita tu tarea' : 'Añadir tarea al proyecto actual'}"
                    value="${editar ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" value="${editar ? 'Editar tarea' : 'Añadir tarea'}" class="submit-nueva-tarea">
                    <button type="button" class="cerrar-modal">Cerrar</button>
                </div>
            </form>

        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal') || e.target.classList.contains('modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(e.target.classList.contains('submit-nueva-tarea')){

                const nombreTarea = document.querySelector('#tarea').value.trim();
                if(nombreTarea === ''){
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', '.formulario legend');
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
                
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(mensaje, tipo, referencia){
        const alertaPrevia = document.querySelector('.alertas');
        if(alertaPrevia){
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alertas', tipo);
        alerta.textContent = mensaje;

        document.querySelector(referencia).after(alerta);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    // Consultar el servidor para añadir una nueva tarea
    async function agregarTarea(tarea){
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, '.formulario legend');

            if(resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);

                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0", 
                    proyectoId: resultado.proyectoId
                };

                tareas = [...tareas, tareaObj];
                mostrarTarea();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto(){
        
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
       return proyecto.id;

    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');

        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

    function cambiarEstadoBoton(tarea){
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea){
        const {estado, id, nombre, proyectoId} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tareas/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if(resultado.respuesta.tipo === "exito"){
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                )

                const modal = document.querySelector('.modal');
                if(modal){ modal.remove(); }

                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }

                    return tareaMemoria;
                });

                mostrarTarea();
            }
            
        } catch (error) {
            console.log(error);
        }
    }

    function botonEliminarTarea(tarea){
        Swal.fire({
            title: "¿Estas seguro?",
            text: "Una vez eliminado no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
        if (result.isConfirmed){ 
            Swal.fire({ title: "¡Eliminado!", text: "La tarea ha sido eliminada exitosamente", icon: "success" });
            eliminarTarea(tarea);
        }
        });
    }

    async function eliminarTarea(tarea){
        const {estado, id, nombre} = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = "/api/tareas/eliminar";
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            if(resultado.resultado){
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTarea();
            }
        } catch (error) {
            console.log(error)
        }
    }
})()