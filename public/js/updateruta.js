$(function () {
    var ruta=$("#ruta");
    $(".content").append(ruta);
    var clicks=0;
    $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > a > i").on("click",function(ev){
        ev.preventDefault();
        clicks++;
        if (clicks%2==0){
            $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > ul").hide();
        }else{
            $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > ul").show();
        }
        debugger;
    });
    var clicks2=0;
    $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > a > span.user-avatar > span > i.user-avatar-icon-foreground.fa.fa-user.fa-stack-1x.fa-inverse").on("click",function(ev){
        ev.preventDefault();
        clicks2++;
        if (clicks2%2==0){
            $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > ul").hide();
        }else{
            $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > ul").show();
        }
    });
    var id=$("#id").val();
    $.getJSON("/apis/ruta/"+id, function (data, status, error) {
        var items=[];
        var rutaid={
            id: data.ruta.id,
            titulo: data.ruta.titulo,
            descripcion: data.ruta.descripcion,
            foto: data.ruta.foto,
            fecha_inicio: data.ruta.fecha_inicio,
            fecha_fin: data.ruta.fecha_fin,
            coordenadas: data.ruta.coordenadas,
            aforo: data.ruta.aforo
        }

        $(".Otro").each(function() {
            var divId = parseInt($(this).attr('id'));
            items.push(divId);
        });

        console.log(items);

        var lat= rutaid.coordenadas.split(",")[0];
        var lon=rutaid.coordenadas.split(",")[1]
        $("#mapa").dialog({
            autoOpen: false,
            modal: true,
            height: 500,
            width: 500,
            buttons: {
              Cerrar: function() {
                $(this).dialog("close");
              }
            },
            close: function(){
              $(this).dialog("close");
            },
            show: {
              effect: "blind", // Puedes personalizar el efecto de apertura del diálogo
              duration: 500
            },
            hide: {
              effect: "blind", // Puedes personalizar el efecto de cierre del diálogo
              duration: 500
            }
        });
        $("#abreMapa").on("click",function(ev){
            ev.preventDefault();
            $("#mapa").dialog("open");
        });
        $("#padre").tabs();
        $('#si').datepicker({
            container: '#si',
            autoclose: true,
            todayHighlight: true,
            calendarWeeks: true,
            format: 'dd/mm/yyyy',
            language: 'es',
            multidate: true
        });
        var direccion=$("#puntoEncuentro");
        var coord=rutaid.coordenadas;
        var map = L.map('mapa').setView([lat, lon], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
        // Utilizar el servicio de geocodificación de OpenStreetMap Nominatim
        var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(direccion);
        $.ajax({
            url: nominatimURL,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
    
                    var marker=L.marker([lat, lon],{draggable: true})
                    
                    marker.addTo(map)
                        .openPopup();
    
                    marker.on("dragend",function(ev){
                        var latitudfin=ev.target._latlng.lat;
                        var longitudfin=ev.target._latlng.lng;
                        coord=latitudfin+","+longitudfin; 
                        alert(coord);
                        $("#mapa").dialog("close");
                    })
                } else {
                    alert("No se encontraron coordenadas para la dirección proporcionada.");
                }
            },
            error: function () {
                alert("Error al obtener las coordenadas. Por favor, inténtalo de nuevo.");
            }
        });
        var desc=$("#descripcion").richText();
        $(".richText-editor div").html(rutaid.descripcion);
        $("#titulo").val(rutaid.titulo);
        $("#aforo").val(rutaid.aforo);
        $('#range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY', // Formato de fecha
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                customRangeLabel: 'Rango personalizado',
            },
            startDate: rutaid.fecha_inicio.split("T")[0].replace(/-/g, '/'),
            endDate: rutaid.fecha_fin.split("T")[0].replace(/-/g, '/')
        });

        $('.ranges li.active').css('background-color', '#337ab7');

        $(".Divs").draggable({
            draggable: true
        });
        
        $(".Otro").draggable({
            draggable: true
        });

        
        
        $("#otrosContainer").droppable({
            accept: ".Divs",
            drop: function(event, ui) {
                var divArrastrable = ui.draggable;
                divArrastrable.appendTo($("#otrosContainer"));
        
                // Retrasar la actualización de la clase
                setTimeout(function() {
                    divArrastrable.removeClass('Divs').addClass('Otro');
                }, 0);
        
                var id = parseInt(divArrastrable.attr('id'));
                items.push(id);
                alert(items);
        
                // Establecer la posición después del retraso
                setTimeout(function() {
                    divArrastrable.css({
                        'position': 'static'
                    });

                    divArrastrable.draggable({
                        draggable: true
                    });
                }, 0);
            }
        });
        
        $("#itemsContainer").droppable({
            accept: ".Otro", // Keep this as 'Otro' since you're using this class
            drop: function(event, ui) {
                var divArrastrable1 = ui.draggable;
                divArrastrable1.appendTo($("#itemsContainer"));
                divArrastrable1.removeClass('Otro').addClass('Divs');
                var id = parseInt(divArrastrable1.attr('id'));
                var index = items.indexOf(id);
                if (items.length>0){
                    if (index !== -1) {
                        items.splice(index, 1);
                    }
                }
                alert(items);
                divArrastrable1.css({
                    'position': 'static'
                });

                divArrastrable1.draggable({
                    draggable: true
                });
            }
        });

        $("#editar1").on("click", function(ev){
            ev.preventDefault();
            var num_errores=0;
            var titulo = $("#titulo").val();
            var descripcion = $(".richText-editor div").html();
            var aforo = $("#aforo").val();
            var rango=$("#range").val();
            var fechas=rango.split("-");
            if (titulo.length == 0) {
                num_errores++;
                $("#errortitulo").show();
            }
    
            if (aforo <= 0) {
                num_errores++;
                $("#erroraforo").show();
            }
    
            if (rango == "") {
                num_errores++;
                $("#errorperiodo").show();
            }
    
            if (coord == "") {
                num_errores++;
                $("#errormapa").show();
            }
    
            if (items.length == 0) {
                num_errores++;
                $("#erroritems").show();
            }

            if (num_errores==0){
                var ruta={
                    Titulo: titulo,
                    Descripcion: descripcion,
                    Aforo: aforo,
                    FechaInicio: fechas[0].replace(/\//g, "-"),
                    FechaFin: fechas[1].replace(/\//g, "-"),
                    Items: items,
                    punto_encuentro: coord
                }
    
                var formdata=new FormData();
                formdata.append('json',JSON.stringify(ruta));
                formdata.append('imagen',$("#fileupload")[0].files[0]);
    
                console.log(JSON.stringify(ruta));
    
                $.ajax({
                    url: "http://localhost:8000/updateruta/"+id,
                    type: 'POST',
                    dataType: 'json',
                    data: formdata,
                    contentType: false,
                    processData: false, 
                    success: function (data) {
                        alert("Editada la ruta correctamente");
                    },
                    error: function () {
                        alert("Error al editar la ruta. Por favor, inténtalo de nuevo.");
                    }
                });
            }
        });
        
        
        
        
    });
});