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
    <style>
    
    #formularioInscripcion {

        display:none;

    }

    #listaCitas {
        
        display:none;

    }
    
    </style>
</head>
<body>

    <h1>Clínica Trescasas<h1>
    <div id="formularioInscripcion">
        Nombre: <input type="text" id="nombre">
        Apellido: <input type="text" id="apellido">
        País: <input type="text" id="pais">
        Día: <input type="date" id="fecha">
        <button id="enviar">Nueva Cita</button>

    </div>
    <div id="listaCitas">

        <h2>Lista de citas para este mes</h2>
        <table id="tablaCitas">
            <tr>
                <th>Nombre completo</th>
                <th>Fecha</th>
            </tr>
        </table>

    </div>

    <button id="nueva">Nueva Cita</button>
    <button id="lista">Obtener Citas</button>

    <script>

        $("#nueva").click(function() {
            $("#formularioInscripcion").show();
            $("#listaCitas").hide();
        });

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

        $("#enviar").click(function() {
            if (validar()) {
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


