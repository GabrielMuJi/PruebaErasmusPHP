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

<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

session_start();
$_SESSION["listaCitas"];
$_SESSION["listaCitasSemana"];

// Clase Cliente
class Cliente {
    // Declaración de las propiedades
    public $nombre;
    public $apellido;
    public $pais;
     
    // Constructor
    public function __construct($nombre, $apellido, $pais) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->pais = $pais;
    }
     
    //Métodos para obtener y modificar datos.
    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getPais() {
        return $this->pais;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setPais($pais) {
        $this->pais = $pais;
    }
    
}

// Clase Cita
class Cita {
    // Declaración de las propiedades
    public $nombreCliente;
    public $fecha;

    // Constructor
    public function __construct($nombreCliente, $fecha){
        $this->nombreCliente = $nombreCliente;
        $this->fecha = $fecha;
    }

    //Métodos para obtener y modificar datos.
    public function getCliente() {
        return $this->nombreCliente;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setCliente($nombreCliente) {
        $this->nombreCliente = $nombreCliente;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

}

// Clase Calendario
class Calendario {

    //Declaración de las propiedades
    public $listaCitas;
    public $citasSemana;

    // Constructor
    public function __construct(){
        $this->listaCitas = array();
        $this->citasSemana = array();
    }

    // Métodos para crear y obtener lista de citas.
    public function crearCita($fecha, Cliente $cliente) {
        //Obtengo el nombre del cliente, y se lo paso al constructor de Cita
        $nombreCliente = $cliente->getNombre()." ".$cliente->getApellido();
        $cita = new Cita($nombreCliente, $fecha);
        //Añado la cita a mi array de citas.
        $this->listaCitas[] = $cita;
        //Lo almaceno todo en la variable de sesión.
        $_SESSION["listaCitas"] = $this->listaCitas;
    }

    public function obtenerCitas() {
        $todasCitas = $_SESSION["listaCitas"];
        $mesActual = date("m");
        foreach ($todasCitas as $cita) {
            $fecha = date_create_from_format('j-m-Y', $cita->getFecha());
            if (date_format($fecha, "w")!="0" && date_format($fecha, "w")!="6" && date_format($fecha, "m")==$mesActual) {
                $this->citasSemana[] = $cita;  
            }
        }
        $_SESSION["listaCitasSemana"] = $this->citasSemana;
        return $_SESSION["listaCitasSemana"];
    }

}

$calendario = new Calendario();
$cliente = new Cliente ("Fernando","Pérez","Polonia");
$cliente2 = new Cliente ("Juan Carlos","Fernández","España");
$calendario->crearCita("03-05-2020",$cliente);
$calendario->crearCita("24-01-2020",$cliente);
$calendario->crearCita("05-12-2020",$cliente2);
$calendario->crearCita("05-02-2020",$cliente2);
print_r($calendario->obtenerCitas());

?>

    <h1>Clínica Trescasas<h1>
    <div id="formularioInscripcion">
        Nombre: <input type="text" id="nombre">
        Apellido: <input type="text" id="apellido">
        País: <input type="text" id="pais">
        Día: <input type="date" id="fecha">
        <button id="enviar">Nueva Cita</button>

    </div>
    <div id="listaCitas">

    Debería ser invisible 2

    </div>

    <button id="nueva">Nueva Cita</button>
    <button id="lista">Obtener Citas</button>




    <script>

        $( "#nueva" ).click(function() {
            $("#formularioInscripcion").show();
            $("#listaCitas").hide();
        });

        $( "#lista" ).click(function() {
            $("#formularioInscripcion").hide();
            $("#listaCitas").show();
        });

        $("#enviar").click(function() {
            if (validar()) {
                fechaFormateada = "";
                fecha = $("#fecha").val().split("-");
                fechaFormateada += fecha[2]+"-";
                fechaFormateada += fecha[1]+"-";
                fechaFormateada += fecha[0];
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


