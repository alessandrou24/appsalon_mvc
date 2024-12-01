let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: [],
    id: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});


function iniciarApp(){
   mostrarSeccion();
   tabs() // Cambia de seccion
   botonesPaginador()
   paginaSiguiente();
   paginaAnterior();
   consultarAPI();
   nombreCliente();
   idCliente();
   seleccionarFecha();
   seleccionarHora();
   mostrarResumen();
}

function mostrarSeccion(){
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
        seccionAnterior.classList.add('ocultar');
       
    }
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');
    seccion.classList.remove('ocultar');


    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
  
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton =>{
        boton.addEventListener('click', function(e){
           paso = parseInt(e.target.dataset.paso);
           mostrarSeccion();
           botonesPaginador();

           
        });
    })
}

function botonesPaginador(){
    const anterior  = document.querySelector('#anterior');
    const siguiente = document.querySelector('#siguiente');

    if(paso === 1){
        anterior.classList.add('ocultar');
        siguiente.classList.remove('ocultar');
    }

    else if(paso === 3){
        anterior.classList.remove('ocultar');
        siguiente.classList.add('ocultar');
        mostrarResumen();
    }

    else if(paso === 2){
        anterior.classList.remove('ocultar');
        siguiente.classList.remove('ocultar');
    }
    
}

function paginaSiguiente(){
    const siguiente = document.querySelector('#siguiente');
    siguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
            paso++;
            mostrarSeccion();
            botonesPaginador();
    });
}

function paginaAnterior(){
    const anterior = document.querySelector('#anterior');
    anterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
            paso--;
            mostrarSeccion();
            botonesPaginador();
    });
}

async function consultarAPI(){
    try{
        const url = `${location.origin}/api/servicios`;
        
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostraarServicios(servicios);
        
    }catch(e){
        console.log(e);
    }
}

function mostraarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');  
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        }
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio){
       const {servicios} = cita;
       const divServicio = document.querySelector(`[data-id-servicio="${servicio.id}"]`);
       if(servicios.some(agregado => agregado.id === servicio.id)){
           cita.servicios = servicios.filter(agregado => agregado.id !== servicio.id);
           divServicio.classList.remove('seleccionado');
       }else {
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
       }
      


}

function idCliente(){
    const id = document.querySelector('#id').value;
    cita.id = id;
}

function nombreCliente(){
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
}

function seleccionarFecha(){
    const fecha = document.querySelector('#fecha');
    fecha.addEventListener('input', function(e){
       const dia = new Date(e.target.value).getUTCDay();
       if([6,0].includes(dia)){ 
        mostrarAlerta('La fecha no es válida', 'error', '.formulario');
        cita.fecha = ''; 
       }else{
        cita.fecha = e.target.value;
       }
    });

    
}

function mostrarAlerta(mensaje, tipo, elemento, desaparecer = true){
    const alertaAnterior = document.querySelector('.alerta');
    if(alertaAnterior) alertaAnterior.remove();
    
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;   
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const formulario = document.querySelector(elemento);
    formulario.appendChild(alerta);
    if(desaparecer){
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }   
    ;
}



function seleccionarHora(){
    const hora = document.querySelector('#hora');
    hora.addEventListener('input', function(e){
        const horaValue = e.target.value;
        const horaArray = horaValue.split(':');
        if(horaArray[0] < 10 || horaArray[0] > 18){
            mostrarAlerta('La hora no es válida', 'error', '.formulario');
            cita.hora = '';
        }else{
            cita.hora = horaValue;
        }
    });
}

async function reservarCita(){
    const idServicios = cita.servicios.map(servicio => servicio.id);
    const datos = new FormData();
    datos.append('fecha', cita.fecha);
    datos.append('hora', cita.hora);
    datos.append('usuarioId', cita.id);
    datos.append('servicios', idServicios);


    try {
    const url = `${location.origin}/api/citas`;
    const respuesta  = await fetch(url, {
        method: 'POST',
        body: datos
    });

    const resultado = await respuesta.json();
    if(resultado.resultado){
        Swal.fire({
            icon: "success",
            title: "Cita creada",
            text: "Tu cita ha sido creada",
            button: 'OK'
          }).then(() => {window.location.reload()});
    } 
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error",
          });
    } 
    }

   


function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');
   
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Por favor, rellena todos los campos', 'error', ".contenido-resumen", false);
        return;
    }
    const {nombre, fecha, hora, servicios} = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    const fechaObj = new Date(fecha);
    const mes = fechaObj.getUTCMonth();
    const dia = fechaObj.getUTCDate();
    const anio = fechaObj.getUTCFullYear();
  
    const fechaUTC = new Date(Date.UTC(anio, mes, dia));
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaEs = fechaUTC.toLocaleDateString('es-ES', opciones);

    const fechaCliente = document.createElement('P');
    fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaEs}`;

    const horaCliente = document.createElement('P');
    horaCliente.innerHTML = `<span>Hora:</span> ${hora}`;

    const headerCita = document.createElement('H3');
    headerCita.classList.add('header-cita');
    headerCita.textContent = 'Cita';
    resumen.appendChild(headerCita);

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    const header = document.createElement('H3');
    header.classList.add('header-servicios');
    header.textContent = 'Servicios';
    resumen.appendChild(header);

    servicios.forEach(servicio => {
        const contendedorServicio = document.createElement('DIV'); 
        contendedorServicio.classList.add('contenedor-servicio');

        const {nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.innerHTML = `<span>Servicio:</span> ${nombre}`;
        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $ ${precio}`;

        contendedorServicio.appendChild(nombreServicio);
        contendedorServicio.appendChild(precioServicio);
        resumen.appendChild(contendedorServicio);
    });

    const boton = document.createElement('BUTTON');
    boton.classList.add('boton');
    boton.textContent = 'Reservar';
    boton.onclick = reservarCita;
    resumen.appendChild(boton);
     
}


