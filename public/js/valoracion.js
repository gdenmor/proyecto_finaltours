$(function() {
    $('#boton').on('click', function(ev) {
        debugger;
        ev.preventDefault();
        var reservaId = $(this).closest('.card').find(".valoracion-container").data('reserva-id');
        var tourId = $(this).closest('.card').find(".valoracion-container").data('tour-id');
        var rating = $(this).closest('.card').find('.valoracion-container input[name="rating"]:checked').val();
        var comentario = $("#com").val();
        if (rating) {
            var json=JSON.stringify({idReserva: reservaId,idtour: tourId,valoracion: rating,comentarios:comentario});
            $.ajax({
                url: "http://localhost:8000/api/addvaloracion",
                type: 'POST',
                dataType: 'json',
                data: json,
                success: function (data) {
                    alert(data.message);
                    window.location.reload();
                },
                error: function(data){
                    alert("Error");
                }
            });
            console.log(json);
        } else {
            $("#error").show();
        }
    });
});