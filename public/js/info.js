$(function(){
    var map = L.map('mapa').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    var id=$("#main").data("id");
    var lat="";
    var lon="";
    $.getJSON("/apis/tour/"+id,function(datos){
        if (datos) {
            debugger;
            var coord=datos.ruta.geolocalizacion.split(",");
            lat = coord[0];
            lon = coord[1];
            var nominatimURL = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lon;

        $.ajax({
            url: nominatimURL,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data) {
    
                    var latitud = data.lat;
                    var longitud = data.lon;
    
                    var marker=L.marker([latitud, longitud],{draggable: false})
                    
                    marker.addTo(map)
                        .openPopup();
                } else {
                    alert("No se encontraron coordenadas para la dirección proporcionada.");
                }
            },
            error: function () {
                alert("Error al obtener las coordenadas. Por favor, inténtalo de nuevo.");
            }
        });

        }
    });

    // Utilizar el servicio de geocodificación de OpenStreetMap Nominatim
    $("#suma").on("click",function(ev){
        ev.preventDefault();
        if (parseInt($("#reserva_num_personas").val())>=8){
            $("#reserva_num_personas").val(parseInt(1));
        }else{
            $("#reserva_num_personas").val(parseInt($("#reserva_num_personas").val())+1);
        }
    });

    $("#resta").on("click",function(ev){
        ev.preventDefault();
        if (parseInt($("#reserva_num_personas").val())<=1){
            $("#reserva_num_personas").val(parseInt(1));
        }else{
            $("#reserva_num_personas").val(parseInt($("#reserva_num_personas").val())-1);
        }
    });
})