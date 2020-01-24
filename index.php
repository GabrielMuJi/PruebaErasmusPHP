<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prueba Erasmus PHP</title>
    <script src="jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>

    body {
        background-color:lightblue;
    }

    h1 {
        padding:10px;
    }

    p {
        font-size:1rem;
    }
    
    #formularioInscripcion {
        padding:10px;
        background-color:lightblue;
        font-size:1rem;
        display:none;
    }

    #listaCitas {
        padding:10px;
        background-color:lightblue;
        font-size:1rem;
        display:none;
    }

    #tablaCitas {
        padding:10px;
        border: solid black;
    }

    #tablaCitas td {
        border: solid black;
    }

    #botones {
        padding:10px;
        font-size:1rem;
    }
    
    </style>
</head>
<body>

    <h1>Clínica Trescasas<h1>
    <div id="formularioInscripcion" class="card">
        <div class="cardbody">
            <input type="text" id="nombre" placeholder="Nombre"></br>
            <input type="text" id="apellido" placeholder="Apellido"></br>
            <input type="text" id="pais" placeholder="Pais"></br>
            <input type="date" id="fecha"></br>
            <button id="enviar">Nueva Cita</button>
        </div>
    </div>
    <div id="listaCitas">
        <h2>Lista de citas para este mes.</h2>
        <table id="tablaCitas">
            <tr>
                <th>Nombre completo</th>
                <th>Fecha</th>
            </tr>
        </table>
    </div>
    <div id="botones">
    <p>Para crear una nueva cita, pulse 'Creación Citas', para obtener las citas del mes, pulse 'Obtener Citas'.</p>
        <button id="nueva">Creación Citas</button>
        <button id="lista">Obtener Citas</button>
    </div>

    <script>

        //Al pulsar en el botón, se muestra el formulario de registro, y se oculta la tabla.
        $("#nueva").click(function() {
            $("#formularioInscripcion").show();
            $("#listaCitas").hide();
        });

        //Al pulsar en el botón, se muestra la tabla y se oculta el formulario de registro.
        //A su vez, la tabla se carga con los datos de las citas del mes actual que séan entre semana.
        $("#lista").click(function() {
            $("#formularioInscripcion").hide();
            $("#listaCitas").show();
            $("#tablaCitas").html("<tr><th>Nombre completo</th><th>Fecha</th></tr>");
            $.get("logicaNegocio.php", function(data, status){
                let json = JSON.parse(data);
                for (cita of json) {
                    $("#tablaCitas").append(`<tr><td>${cita.nombreCliente}</td><td>${cita.fecha}</td></tr>`)
                }
            });
        });

        //Al pulsar el botón, compruebo cada campo para ver que están todos completos.
        //Luego, envío los datos mediante POST para registrarlos en la lista de citas.
        $("#enviar").click(function() {
            if (validar()) {
                //Modifico la fecha para que se ajuste con el formato de la lógica de negocio.
                fechaFormateada = "";
                fecha = $("#fecha").val().split("-");
                fechaFormateada += fecha[2]+"-";
                fechaFormateada += fecha[1]+"-";
                fechaFormateada += fecha[0];
                $.post("logicaNegocio.php",
                {
                    nombre: $("#nombre").val(),
                    apellido: $("#apellido").val(),
                    pais: $("#pais").val(),
                    fecha: fechaFormateada
                },
                function(data, status){
                    alert(data);
                });
            }
        });

        //Comprueba que no haya campos vacios.
        function validar() {
        if ($("#nombre").val()=="" || $("#apellido").val()=="" || $("#pais").val()=="" || $("#fecha").val()=="") {
            alert("Todos los campos son obligatorios");
            return false;
        }
        return true;
    }

    </script>

</body>
</html>


