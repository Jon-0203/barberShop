let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
  id: "",
  nombre: "",
  hora: "",
  fecha: "",
  servicios: [],
};

document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

function iniciarApp() {
  mostrarInfo(); //muestra y oculta las secciones
  tabs(); //Cambia la seccion cuando se presiona los tabs
  botPaginador(); // Agrega o quita los botones del paginador
  pagSiguiente();
  pagAnterior();

  consultaApi(); //consulta api en el backend

  idCliente();
  mostrarNombre(); //Añade el nombre del cliente al objeto cita
  seleccionarFecha(); //Añade la fecha de la cita al objeto
  seleccionarHora(); //Añade lahora de la cita al objeto
  mostrarResumen(); //Mostrar resumen de tu cita
}

function mostrarInfo() {
  //ocultar la seccion que tenga la clase de info
  const infoAnterior = document.querySelector(".mostrar");
  if (infoAnterior) {
    infoAnterior.classList.remove("mostrar");
  }

  const pasoInfo = `#paso-${paso}`; //nos traemos id y la variables declarada
  //seleccionar la seccion con el paso
  const info = document.querySelector(pasoInfo);
  info.classList.add("mostrar"); //mostramos la info de cada seccion

  //Quita la clase actual a la anterior
  const tabAnterior = document.querySelector(".actual");
  if (tabAnterior) {
    tabAnterior.classList.remove("actual");
  }

  //Resalta el tab actual
  const tabPaso = `[data-paso="${paso}"]`;
  const tab = document.querySelector(tabPaso);
  tab.classList.add("actual");
}

function tabs() {
  //Query selector se usa para escojer varios botones
  const botones = document.querySelectorAll(".tabs button");
  //hacer esta operacion cuando tenemos algunos botones
  botones.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      paso = parseInt(e.target.dataset.paso);

      mostrarInfo();

      botPaginador();
    });
  });
}

function botPaginador() {
  const pagAnterior = document.querySelector("#anterior");
  const pagSiguiente = document.querySelector("#siguiente");

  if (paso === 1) {
    pagAnterior.classList.add("ocultar");
    pagSiguiente.classList.remove("ocultar");
  } else if (paso === 3) {
    pagAnterior.classList.remove("ocultar");
    pagSiguiente.classList.add("ocultar");
    mostrarResumen();
  } else {
    pagAnterior.classList.remove("ocultar");
    pagSiguiente.classList.remove("ocultar");
  }
  mostrarInfo();
}

function pagSiguiente() {
  const pagSiguiente = document.querySelector("#siguiente");
  pagSiguiente.addEventListener("click", function () {
    if (paso >= pasoFinal) return;
    paso++;
    botPaginador();
  });
}

function pagAnterior() {
  const pagAnterior = document.querySelector("#anterior");
  pagAnterior.addEventListener("click", function () {
    if (paso <= pasoInicial) return;
    paso--;
    botPaginador();
  });
}
//Funcion asincrona
async function consultaApi() {
  try {
    const url = "http://localhost:3000/api/servicios";
    //fetch nos permite usar nuestro url/servicio ** async await
    const resultado = await fetch(url);
    const servicios = await resultado.json();

    mostrarServicios(servicios);
  } catch (error) {
    console.log(error);
  }
}

function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio; //destructuring

    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("nombre-servicio");
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.classList.add("precio-servicio");
    precioServicio.textContent = precio; //template string `€ €{precio}`

    const servicioDiv = document.createElement("DIV");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    //Inyectamos los datos de la BD
    document.querySelector("#servicios").appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;
  //... Crea una copia de servicios la inyecta    cita.servicios = [...servicios, servicio];
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  //Comprobar si un servicio ya fue agregado ** some verifica si en el arreglo ya esta un elemento
  //Identificar al elemento que damos click
  if (servicios.some((agregado) => agregado.id === id)) {
    //Elimnarlo
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    divServicio.classList.remove("seleccionado");
  } else {
    //Agregarlo
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add("seleccionado");
  }
  console.log(cita);
}

function idCliente() {
  cita.id = document.querySelector("#id").value;
}

function mostrarNombre() {
  cita.nombre = mostrarNombre = document.querySelector("#nombre").value;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector("#fecha");
  inputFecha.addEventListener("input", function (e) {
    const dia = new Date(e.target.value).getUTCDay();
    //Codigo para L A V
    if ([6, 0].includes(dia)) {
      //Se resetea el valor
      e.target.value = "";
      mostrarAlerta("Fines de semana no permitidos", "error", ".formulario");
    } else {
      //Se asigna nuevo valor
      cita.fecha = e.target.value;
      //Codigo de L A D.
      // cita.fecha = inputFecha.value;
    }
  });
}

function seleccionarHora() {
  const mostrarHora = document.querySelector("#hora");
  mostrarHora.addEventListener("input", function (e) {
    const horaCita = e.target.value;
    const hora = horaCita.split(":")[0];
    if (hora < 9 || hora > 21) {
      e.target.value = "";
      mostrarAlerta("Hora no valida", "error", ".formulario");
    } else {
      //Se asigna nuevo valor
      cita.hora = e.target.value;
      console.log(cita);
    }
  });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  //Previene a que se genere mas de una alerta
  const alertaUnica = document.querySelector(".alerta");
  if (alertaUnica) {
    alertaUnica.remove();
  }
  //Scripting para crear la alerta
  const alerta = document.createElement("DIV");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta");
  alerta.classList.add(tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  if (desaparece) {
    //codigo para que alerta se elimine
    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }
}

function mostrarResumen() {
  const resumen = document.querySelector(".resumen-cita");

  //Limpiar contenido resumen
  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }
  //Object.values accedemos al valor
  if (Object.values(cita).includes("") || cita.servicios.length === 0) {
    mostrarAlerta(
      "Faltan datos de servicios, Fecha u hora",
      "error",
      ".resumen-cita",
      false
    );
    return; //detiene la ejecucion el else
  }

  //Formatear el div de resumen
  const { nombre, fecha, hora, servicios } = cita;

  //Cabecera para servicios en resumen
  const cabeceraServicios = document.createElement("H3");
  cabeceraServicios.textContent = "Resumen de servicios";
  resumen.appendChild(cabeceraServicios);
  //Iterando y mostrando los servicios
  servicios.forEach((servicio) => {
    //Aplico destructuring
    const { id, precio, nombre } = servicio;

    const contenedorServicio = document.createElement("DIV");
    contenedorServicio.classList.add("contenedor-servicio");

    const textoServicio = document.createElement("P");
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.innerHTML = `<span>Precio: </span>${precio} euros`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    resumen.appendChild(contenedorServicio);
  });

  //Cabecera para cita en resumen
  const cabeceraCita = document.createElement("H3");
  cabeceraCita.textContent = "Resumen de Cita ";
  resumen.appendChild(cabeceraCita);

  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre : </span>${nombre}`;

  //Formatear la fecha en español;
  const ObjFecha = new Date(fecha);
  const mes = ObjFecha.getMonth();
  const dia = ObjFecha.getDate();
  const year = ObjFecha.getFullYear();

  const fechaUTC = new Date(Date.UTC(year, mes, dia));

  const opciones = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fechaFormateada = fechaUTC.toLocaleDateString("es-ES", opciones);
  console.log(fechaFormateada);

  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha : </span>${fecha}`;

  const horaCita = document.createElement("P");
  horaCita.innerHTML = `<span>Hora : </span>${hora} horas`;

  //Boton reservar
  const botReservar = document.createElement("BUTTON");
  botReservar.classList.add("boton");
  botReservar.textContent = "Reservar cita";
  botReservar.onclick = reservarCita;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
  resumen.appendChild(botReservar);
}
//Metodo utilizando fecth form-data
async function reservarCita() {
  const { nombre, hora, fecha, servicios, id } = cita;

  const idServicios = servicios.map((servicio) => servicio.id);

  //console.log(idServicios);

  const datos = new FormData();

  datos.append('hora', hora);
  datos.append('fecha', fecha);
  datos.append('usuarioId', id);
  datos.append('servicios', idServicios);
  //console.log(...datos)... Speed operator hace copia de formdata y lo resetea

  try {
          //Peticion hacia la API
    const url = 'http://localhost:3000/api/citas'
    const respuesta = await fetch(url, {
      method: 'POST',
      body: datos,
    });

    const resultado = await respuesta.json();
    console.log(resultado.resultado);

    //Utilizando librerias SweetAlerta2
    if(resultado.resultado) {
      Swal.fire({
        icon: 'success',
        title: 'Cita creada',
        text: 'Cita creada correctamente',
        button: 'OK',
      }).then(() => {
        setTimeout(() => {
          //Recargamos la pagina y volvemos al inicio
          window.location.reload();
        }, 2000);
      })
    }  
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'A ocurrido un error!',
    })
  }

}
