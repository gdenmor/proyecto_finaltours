$(function(){
    $(".si").on("click", function(ev){
        ev.preventDefault();
        var tr=$(this).parent();
        tr.find("input[type=number]").prop("disabled",false);
    });
    $(".no").on("click", function(ev){
        ev.preventDefault();
        var tr=$(this).parent();
        tr.find("input[type=number]").prop("disabled",true);
    });
    $("#enviar").on("click", function(ev){
        ev.preventDefault();
        var numpersonasasiste = 0;

        num_errores=0;
        
        $("input[type=number]").each(function() {
            if (parseInt($(this).val())>=0&&parseInt($(this).val())<=$(this).parent().find(".num_personas")){
                numpersonasasiste += parseInt($(this).val()) || 0;
            }else{
                num_errores++;
            }
        });


        if (num_errores==0){
            var obj={
                num_personas:numpersonasasiste
            }
    
            var URLactual = window.location.pathname;
            var idtour=URLactual.split("/")[3];
    
            $.ajax({
                url: "http://localhost:8000/pasalista/"+idtour,
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify(obj),
                contentType: false,
                processData: false, 
                success: function (data) {
                    alert("Se pasó lista correctamente");
                    window.location.href="http://localhost:8000/lista/"+URLactual.split("/")[2];
                },
                error: function () {
                    alert("Error al editar la ruta. Por favor, inténtalo de nuevo.");
                }
            });
        }else{
            alert("Error");
        }
    });
});
