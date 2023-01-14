let paso=1;
const pasoInicial = 1;
const pasoFinal =3;

const cita = {
    nombre:'',
    fecha:'',
    hora:'',
    servicios:[]
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarInfo();//muestra y oculta las secciones
    tabs(); //Cambia la seccion cuando se presiona los tabs
    botPaginador(); // Agrega o quita los botones del paginador
    pagSiguiente();
    pagAnterior();

    consultaApi(); //consulta api en el backend
    mostrarNombre();//Añade el nombre del cliente al objeto cita
    seleccionarFecha();//Añade la fecha de la cita al objeto
    
}

function mostrarInfo(){

    //ocultar la seccion que tenga la clase de info
    const infoAnterior = document.querySelector('.mostrar');
    if(infoAnterior) {
        infoAnterior.classList.remove('mostrar');
    }

    const pasoInfo = `#paso-${paso}`;//nos traemos id y la variables declarada
    //seleccionar la seccion con el paso
    const info = document.querySelector(pasoInfo);
    info.classList.add('mostrar');//mostramos la info de cada seccion

    //Quita la clase actual a la anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tabPaso = `[data-paso="${paso}"]`;
    const tab = document.querySelector(tabPaso);
    tab.classList.add('actual');
     
}

function tabs(){
    //Query selector se usa para escojer varios botones
    const botones = document.querySelectorAll('.tabs button');
    //hacer esta operacion cuando tenemos algunos botones
    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            paso = parseInt (e.target.dataset.paso);

            mostrarInfo();

            botPaginador();
        });
    })
}

function botPaginador() {
    const pagAnterior = document.querySelector('#anterior');
    const pagSiguiente = document.querySelector('#siguiente');
    
    if(paso === 1) {
        pagAnterior.classList.add('ocultar');
        pagSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.add('ocultar');
    } else {
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.remove('ocultar');
    }
   mostrarInfo();
}

function pagSiguiente() {
    const pagSiguiente = document.querySelector('#siguiente');
    pagSiguiente.addEventListener('click', function() {
        if(paso >= pasoFinal) return;
        paso++;
        botPaginador();
    });
   
}

function pagAnterior() {
    const pagAnterior = document.querySelector('#anterior');
    pagAnterior.addEventListener('click', function() {
        if(paso <= pasoInicial) return;
        paso--;
        botPaginador();
    });

}
//Funcion asincrona
async function consultaApi() {

    try {
        const url='http://localhost:3000/api/servicios';
        //fetch nos permite usar nuestro url/servicio ** async await  
        const resultado = await fetch(url);
        const servicios = await resultado.json();

        mostrarServicios(servicios); 

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;//destructuring

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = precio; //template string `€ €{precio}`

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        
        //Inyectamos los datos de la BD
        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {
    const {id} = servicio;
    const {servicios} = cita;
    //... Crea una copia de servicios la inyecta    cita.servicios = [...servicios, servicio];
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

    //Comprobar si un servicio ya fue agregado ** some verifica si en el arreglo ya esta un elemento
    //Identificar al elemento que damos click
    if(servicios.some( agregado => agregado.id === id)){
        //Elimnarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else {
        //Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
    console.log(cita);
} 

function mostrarNombre() {
    cita.nombre = mostrarNombre = document.querySelector('#nombre').value;
    
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function (e) {

        const dia = new Date(e.target.value).getUTCDay();
        //Codigo para L A V
        if( [6, 0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error');
        } else {    
            cita.fecha = e.target.value;
        //Codigo de L A D.
        // cita.fecha = inputFecha.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo) { 
    //Previene a que se genere mas de una alerta
    const alertaUnica = document.querySelector('.alerta');
    if (alertaUnica) return;
    //Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta'); 
    alerta.classList.add(tipo);

    const formulario = document.querySelector('.formulario');
    formulario.appendChild(alerta);

    //codigo para que alerta se elimine
    setTimeout(() => {
       alerta.remove(); 
    }, 3000);
}