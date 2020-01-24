<?php

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$_SESSION["listaCitas"];
$_SESSION["listaCitasSemana"];
$_SESSION["listaClientes"] = array();

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
        //Obtengo el año actual
        $anioActual = date("Y");
        $fechaFormateada = date_create_from_format('j-m-Y', $fecha);
        //Si no coinciden el año actual con el introducido, lanzo la excepción.
        if (date_format($fechaFormateada, "Y")!=$anioActual) {
            throw new Exception('Sólo puedes crear citas para el año actual.');
        } else {
            //Obtengo el nombre del cliente para pasarlo luego al constructor de Citas.
            $nombreCliente = $cliente->getNombre()." ".$cliente->getApellido();
            //Compruebo si el cliente ya está registrado.
            foreach ($_SESSION['listaClientes'] as $persona) {
                if($cliente->getNombre()==$persona->getNombre() && $cliente->getApellido()==$persona->getApellido() && $cliente->getPais()==$persona->getPais()) {
                    $clienteExistente = true;
                } 
            }
            //Si el cliente existe, procedo normalmente.
            if ($clienteExistente) {
                $cita = new Cita($nombreCliente, $fecha);
                //Añado la cita a mi array de citas.
                $_SESSION["listaCitas"][] = $cita;
                guardarCita($cita,"citas.txt");
            }
            //Si el cliente no existe, primero lo registro.
            else {
                $_SESSION['listaClientes'][] = new Cliente($cliente->getNombre(),$cliente->getApellido(),$cliente->getPais());
                guardarCliente(new Cliente($cliente->getNombre(),$cliente->getApellido(),$cliente->getPais()),"clientes.txt");
                $cita = new Cita($nombreCliente, $fecha);
                //Añado la cita a mi array de citas.
                $_SESSION["listaCitas"][] = $cita;
                guardarCita($cita,"citas.txt");
            }
            echo "La cita ha sido registrada.";
        }
    }

    public function obtenerCitas() {
        $todasCitas = $_SESSION["listaCitas"];
        //Obtengo el mes actual
        $mesActual = date("m");
        //Compruebo cada cita, asegurándome que el día de la semana está entre Lunes y Viernes, y que la fecha corresponde a este mes.
        foreach ($todasCitas as $cita) {
            $fecha = date_create_from_format('j-m-Y', $cita->getFecha());
            //0 es Domingo, 6 es Sábado, y m es el mes actual.
            if (date_format($fecha, "w")!="0" && date_format($fecha, "w")!="6" && date_format($fecha, "m")==$mesActual) {
                $this->citasSemana[] = $cita;  
            }
        }
        //Procedo a devolver toda la información en forma de JSON.
        $_SESSION["listaCitasSemana"] = $this->citasSemana;
        return json_encode($_SESSION["listaCitasSemana"]);
    }

}

function guardarCita(Cita $cita, $fichero) {
    #Abrimos el fichero en modo de escritura 
    $stream = fopen($fichero,"a"); 
    #Escribimos la primera línea dentro de él 
    $string = $cita->getCliente()."-".$cita->getFecha()."\r\n";
    fputs($stream,$string); 
    #Cerramos el fichero 
    fclose($stream); 
}

function guardarCliente(Cliente $cliente, $fichero) {
    #Abrimos el fichero en modo de escritura 
    $stream = fopen($fichero,"a"); 
    #Escribimos la primera línea dentro de él 
    $string = $cliente->getNombre()."-".$cliente->getApellido()."-".$cliente->getPais()."\r\n";
    fputs($stream,$string); 
    #Cerramos el fichero 
    fclose($stream); 
}

//Creo un objeto de la clase Calendario
$calendario = new Calendario();
//Compruebo si el usuario está intentando crear una cita.
if (!empty($_POST['nombre'])) {
    $cliente = new Cliente($_POST['nombre'],$_POST['apellido'],$_POST['pais']);
    try {
        $calendario->crearCita($_POST['fecha'],$cliente);
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
} //Si no, es que el cliente está intentando ver las citas.
else {
    echo $calendario->obtenerCitas();
}
?>