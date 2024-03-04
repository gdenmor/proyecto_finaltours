$(function(ev){
    var horario=$("#horario");
    var calendario=$("#calendar");
    debugger;
    $(".content").append(horario);
    var tours=[];
    $.getJSON("/apis/tours", function (data, status) {
        // Verifica si la solicitud fue exitosa
        if (status === "success") {
            // Procesa los datos recibidos
            for (let i = 0; i < data.length; i++) {
                var fecha = new Date(data[i].fecha);
                var hora = new Date(data[i].hora);
                var fechaConHora = new Date(
                    fecha.getFullYear(),
                    fecha.getMonth(),
                    fecha.getDate(),
                    hora.getHours(),
                    hora.getMinutes(),
                    hora.getSeconds(),
                    hora.getMilliseconds()
                );

                console.log(fechaConHora);
                  
                tours[i] = {
                    title: data[i].ruta.titulo+" "+"Guia: "+data[i].guia.nombre+" Aforo: "+data[i].ruta.aforo,
                    start: fechaConHora,
                };
            }
        } else {
            // Maneja el caso de error
            console.error("Error al cargar datos de tours");
        }

    //Crea un FullCalendar
    var calendar = new FullCalendar.Calendar(calendario[0], 
    {
        timeZone: 'GMT+1',
        themeSystem: 'bootstrap5',
        droppable: true,
        headerToolbar: 
        {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        editable: true,
        weekNumbers: true,
        dayMaxEvents: true,
        events: tours,
        dateClick: function(info) {
            // La función que se ejecutará cuando se haga clic en una fecha
            calendar.changeView('dayGridDay', { date: info.dateStr }); // Cambia a la vista diaria y establece la fecha seleccionada
        },
        eventColor: generarNuevoColor()
    });

    calendar.render();

    });
});

function generarNuevoColor(){
	var simbolos, color;
	simbolos = "0123456789ABCDEF";
	color = "#";

	for(var i = 0; i < 6; i++){
		color = color + simbolos[Math.floor(Math.random() * 16)];
	}

	return color;
}