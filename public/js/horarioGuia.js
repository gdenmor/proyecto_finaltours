$(function(ev){
    var currentUrl = window.location.href;

    var urlParts = currentUrl.split('/');

    var id = urlParts[urlParts.length - 1];

    var calendario=$("#calendar");
    var tours=[];
    $.getJSON("/apis/toursguia/"+id, function (data, status) {
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
                    title: data[i].ruta.titulo+" Aforo: "+data[i].ruta.aforo,
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
        selectable: true,
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
            calendar.changeView('dayGridDay'); // Cambia a la vista diaria y establece la fecha seleccionada
            calendar.gotoDate(info.dateStr);
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