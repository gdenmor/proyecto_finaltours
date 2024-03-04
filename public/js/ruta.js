$(function () {
    var ruta = $("#ruta");
    $(".content").append(ruta);
    var clicks = 0;
    $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > a > i").on("click", function (ev) {
        ev.preventDefault();
        clicks++;
        if (clicks % 2 == 0) {
            $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > ul").hide();
        } else {
            $("body > div.wrapper > section > aside > div.dropdown.dropdown-settings > ul").show();
        }
        debugger;
    });
    $("#buscar").on("click", function(ev) {
        ev.preventDefault();
        $("#itemsContainer").empty();
        var provincia = $("#filtro").val();
        
        $.getJSON("/veritems/" + provincia, function(datos, respuesta) {
            if (respuesta === "success") {
                for (let i = 0; i < datos.length; i++) {
                    var newdiv = $("<div>");
                    newdiv.attr('id', datos[i].id);
                    newdiv.attr('class', "Divs");
                    newdiv.append($("<h1>").text(datos[i].titulo));
                    newdiv.append($("<p>").text(datos[i].descripcion));
                    newdiv.append($("<p>").text(datos[i].localidad.provincia.nombre_provincia));
                    $("#itemsContainer").append(newdiv);
                }
                // Hacer los divs con la clase .Divs draggable
                $(".Divs").draggable({
                    draggable: true
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

                $("#otrosContainer").droppable({
                    accept: ".Divs", // Aceptar solo el div con este ID
                    drop: function (event, ui) {
                        var divArrastrable = ui.draggable;
                        divArrastrable.appendTo($("#otrosContainer"));
                        divArrastrable.removeClass('Divs');
                        divArrastrable.addClass('Otro');
                        var id = parseInt(divArrastrable.attr('id'));
                        items.push(id);
                        alert(items);
                        divArrastrable.css({
                            'position': 'static'
                        });

                        divArrastrable.draggable({
                            draggable: true
                        });
                    }
                });
            } else {
                // En caso de error, mostrar un mensaje en un nuevo div
                var errorDiv = $("<div>").text("No se han encontrado items");
                $("#itemsContainer").append(errorDiv);
            }
        });
    });
    var clicks2 = 0;
    $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > a > span.user-avatar > span > i.user-avatar-icon-foreground.fa.fa-user.fa-stack-1x.fa-inverse").on("click", function (ev) {
        ev.preventDefault();
        clicks2++;
        if (clicks2 % 2 == 0) {
            $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > ul").hide();
        } else {
            $("body > div.wrapper > section > aside > div.navbar-custom-menu > div > ul").show();
        }
    });
    $("#mapa").dialog({
        autoOpen: false,
        modal: true,
        height: 500,
        width: 500,
        buttons: {
            Cerrar: function () {
                $(this).dialog("close");
            }
        },
        close: function () {
            $(this).dialog("close");
        },
        show: {
            effect: "blind", // Puedes personalizar el efecto de apertura del diálogo
            duration: 500
        },
        hide: {
            effect: "blind", // Puedes personalizar el efecto de cierre del diálogo
            duration: 500
        },
        open: function (event, ui) {
            // Asegúrate de que el mapa se inicialice después de abrir el modal
            map.invalidateSize();
        }
    });
        var direccion = $("#puntoEncuentro");
        var coord = "";
        var map = L.map('mapa').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
        // Utilizar el servicio de geocodificación de OpenStreetMap Nominatim
        var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(direccion);
    
        $.ajax({
            url: nominatimURL,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
    
                    var marker = L.marker([0, 0], { draggable: true })
    
                    marker.addTo(map)
                        .openPopup();
    
                    marker.on("dragend", function (ev) {
                        var latitudfin = ev.target._latlng.lat;
                        var longitudfin = ev.target._latlng.lng;
                        coord = latitudfin + "," + longitudfin;
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
    $("#abreMapa").on("click", function (ev) {
        ev.preventDefault();
        
        $("#mapa").dialog("open");
    });
    var fechaActual = new Date();

    // Formatear la fecha en el formato dd/mm/yyyy
    var formateada = ('0' + fechaActual.getDate()).slice(-2) + '/' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '/' + fechaActual.getFullYear();
    $("#padre").tabs();
    $('#si').datepicker({
        container: '#si',
        autoclose: true,
        todayHighlight: true,
        calendarWeeks: true,
        format: 'dd/mm/yyyy',
        language: 'es',
        multidate: true,
        startDate: formateada
    });
    $.getJSON("/veritems", function (datos, respuesta) {
        if (respuesta == "success") {
            for (let i = 0; i < datos.length; i++) {
                var newdiv = $("<div>");
                newdiv.attr('id', datos[i].id);
                newdiv.attr('class', "Divs");
                newdiv.append($("<h1>").text(datos[i].titulo));
                newdiv.append($("<p>").text(datos[i].descripcion));
                newdiv.append($("<p>").text(datos[i].localidad.provincia.nombre_provincia));
                $("#itemsContainer").append(newdiv);
            }
            $(".Divs").draggable({
                draggable: true
            });
        }
    });

    var coord = "";

    $("#modaltour").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            Cerrar: function () {
                $(this).dialog("close");
            }
        },
        close: function () {
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
    var items = [];
    $("#descripcion").richText();
    $("#abrirMapa").on("click", function (ev) {
        ev.preventDefault();
    });
    $('#range').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY', // Formato de fecha
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            customRangeLabel: 'Rango personalizado',
        },
        minDate: formateada,
    });
    $("#otrosContainer").droppable({
        accept: ".Divs", // Aceptar solo el div con este ID
        drop: function (event, ui) {
            var divArrastrable = ui.draggable;
            divArrastrable.appendTo($("#otrosContainer"));
            divArrastrable.removeClass('Divs');
            divArrastrable.addClass('Otro');
            var id = parseInt(divArrastrable.attr('id'));
            items.push(id);
            alert(items);
            divArrastrable.css({
                'position': 'static'
            });
        }
    });

    Dropzone.options.myDropzone = {
        paramName: "drop", // Nombre del parámetro que se enviará al servidor
        maxFilesize: 1000, // Tamaño máximo del archivo en megabytes
        acceptedFiles: 'image/*', // Aceptar solo archivos de imagen
        dictDefaultMessage: 'Arrastra y suelta archivos aquí o haz clic para seleccionar', // Mensaje predeterminado
    };
    $("#crear").on("click", function (ev) {
        ev.preventDefault();
        var num_errores = 0;
        var titulo = $("#titulo").val();
        var descripcion = $("#descripcion").val();
        var aforo = $("#aforo").val();
        var rango = $("#range").val();
        var fechas = rango.split("-");
        var formData = new FormData();
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

        if (num_errores == 0) {
            var result=window.confirm("Are you sure you want to continue?");
            var ruta = {
                Titulo: titulo,
                Descripcion: descripcion,
                Aforo: aforo,
                FechaInicio: fechas[0].replace(/\//g, "-"),
                FechaFin: fechas[1].replace(/\//g, "-"),
                Items: items,
                punto_encuentro: coord
            }

            var formdata = new FormData();
            formdata.append('json', JSON.stringify(ruta));
            formdata.append('imagen', $("#fileupload")[0].files[0]);

            alert(JSON.stringify(ruta));

            $.ajax({
                url: "http://localhost:8000/addruta",
                type: 'POST',
                dataType: 'json',
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaltour").dialog("open");
                    var array = [];
                    $('#periodo').daterangepicker({
                        locale: {
                            format: 'DD/MM/YYYY', // Formato de fecha
                            applyLabel: 'Aplicar',
                            cancelLabel: 'Cancelar',
                            customRangeLabel: 'Rango personalizado',
                        }
                    });
                    var id = data.id;
                    var dias = [];
                    $(".dia").on("click", function (ev) {
                        ev.preventDefault();
                        dias = toggleSeleccion($(this));
                    });
                    //$("#tiempo").pickatime();


                    $("#aniade").on("click", function (ev) {
                        ev.preventDefault();
                        var fechaselegidas = $("#periodo").val();
                        var fechasseparadas = fechaselegidas.split("-");
                        var idGuia = parseInt($("#guias").val());
                        var objeto = {
                            fechaInicio: fechasseparadas[0].replace(/\//g, "-"),
                            fechaFin: fechasseparadas[1].replace(/\//g, "-"),
                            diasSemana: dias,
                            idRuta: id,
                            idGuia: idGuia,
                            hora: $("#tiempo").val()
                        }

                        var fila = $("<tr>");
                        for (var prop in objeto) {
                            var celda = $("<td>").text(objeto[prop]);
                            fila.append(celda);
                        }

                        $("#tabla").append(fila);

                        array.push(objeto);
                    });

                    $("#guarda").on("click", function (ev) {
                        ev.preventDefault();
                        alert(array.length);
                        $.ajax({
                            url: "http://localhost:8000/addprogramacion",
                            type: 'POST',
                            dataType: 'json',
                            data: JSON.stringify(array),
                            success: function () {
                                alert("Programación guardada");
                            },
                            error: function (error) {
                                alert("Error al guardar la programación");
                            }
                        });
                        $("#crea").removeAttr("disabled");
                        $(this).attr("disabled", true);
                    });

                    $("#crea").on("click", function (ev) {
                        ev.preventDefault();
                        alert(JSON.stringify(array));
                        $.ajax({
                            url: "http://localhost:8000/addtours",
                            type: 'POST',
                            dataType: 'json',
                            data: JSON.stringify(array),
                            success: function () {
                                alert("Tours creados");
                                $("#modalTour").dialog("destroy");
                            },
                            error: function (error) {
                                alert("Error al guardar la programación");
                            }
                        });
                    });
                }
            });
        }else{
            alert(num_errores);
        }
    });
});


    function toggleSeleccion(elemento) {
        $(elemento).toggleClass('seleccionado');
        var diasSeleccionados = actualizarResultado();
        return diasSeleccionados;
    }

    function actualizarResultado() {
        var diasSeleccionados = [];
        var dias = document.querySelectorAll('.dia.seleccionado');

        dias.forEach(function (dia) {
            diasSeleccionados.push(dia.getAttribute('data-dia'));
        });
        return diasSeleccionados;
    }