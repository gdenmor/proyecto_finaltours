$(function(){
  var fechaActual = new Date();
  var formateada =
    ('0' + fechaActual.getDate()).slice(-2) +
    '/' +
    ('0' + (fechaActual.getMonth() + 1)).slice(-2) +
    '/' +
    fechaActual.getFullYear();

  $("#fecha").daterangepicker({
    locale: {
      format: 'DD/MM/YYYY', // Formato de fecha
      applyLabel: 'Aplicar',
      cancelLabel: 'Cancelar',
      customRangeLabel: 'Rango personalizado',
    },
    minDate: formateada
  });
    //ajustamos los modales según nuestras necesidades
    $("#miModal").dialog({
            autoOpen: false,
            modal: true,
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

    $("#miModal input[type=submit]").css({"margin-top": "5%"});
    var localidades=[];
    $.getJSON("/verlocalidades",function(data,respuesta){
      if (respuesta=="success"){
        for (let i=0;i<data.length;i++){
          localidades[i]=data[i].nombre;
        }

        $("#home-search-text").autocomplete({
          source: localidades
        });
      }
    });

    $("#page-header__search-button").on("click",function(ev){
      ev.preventDefault();
      var localidad=$("#home-search-text").val();
      var resultado=false;
      for (i=0;i<localidades.length;i++){
        if (localidades[i]==localidad){
          resultado=true;
          break;
        }
      }

      var fecha=$("#fecha").val();
      var fechasSeparadas = fecha.split(" - ");

      var fechaInicio = fechasSeparadas[0]
      .split("/")
      .reverse()
      .join('-');

      var fechaFin = fechasSeparadas[1]
      .split("/")
      .reverse()
      .join('-');


      if (resultado==false){
        alert("No existe esta localidad");
      }else{
        document.location.href = "tours/" + localidad + "/" + fechaInicio + "/" + fechaFin;
      }
    });

    $("#login").on("click",function(ev){
      ev.preventDefault();
      $("#miModal").dialog("open");
  });
});