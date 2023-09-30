<?php

    require_once("nusoap.php");
    $namespace = "http://localhost/mysql";
    $server = new soap_server();
    $server->configureWSDL("WSDLTST", $namespace);
    $server->soap_defencoding = 'UTF-8';
    $server->wsdl->schemaTargetNamespace = $namespace;  
    
      function creaContacto($nombre, $direccion, $apellido){

                require_once("conexion.php");
                $conn = mysqli_connect($servername, $username, $password, $dbname)or die("Error de conexión con la base de datos");
                $sql = "INSERT INTO escritor (nombre, direccion, apellido) VALUES ('".$nombre."', '".$direccion."', '".$apellido."')";
                if (mysqli_query($conn, $sql)) {
                    $msg =  "Se introdujo un nuevo registro en la BD; ".$nombre;
                } else {
                    $msg = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                mysqli_close($conn);
                return new soapval('return', 'xsd:string', $msg);
        }



       function buscarContacto($nombre) {

        require_once("conexion.php");
        $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM escritor where nombre='".$nombre."'";

                $resultado = mysqli_query($conn, $sql);
               
                $listado = "<table border='1'><tr><td>ID</td><td>Nombre</td><td>Apellidos</td><td>Direcci&oacute;n</td></tr>";
                while ($fila = mysqli_fetch_array($resultado)){
                        $listado = $listado."<tr><td>".$fila['id_escritor']."</td><td>".$fila['nombre']
                        ."</td><td>".$fila['apellido']."</td><td>".$fila['direccion']."</td></tr>";
                }
                $listado = $listado."</table>";
                mysqli_close($conn);

                
                return new soapval('return', 'xsd:string', $listado);

        }



       function mostrarTodosContactos() {

        require_once("conexion.php");

                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM escritor";

                $resultado = mysqli_query($conn, $sql);
                $listado = "<table border='1'><tr><td>ID</td><td>Nombre</td><td>Apellidos</td><td>Direcci&oacute;n</td><td>Eliminar</td><td>Modificar</td></tr>";
                while ($fila = mysqli_fetch_array($resultado)){
                        $listado = $listado."<tr><td>".$fila['id_escritor']."</td><td>".$fila['nombre']
                        ."</td><td>".$fila['apellido']."</td><td>".$fila['direccion']."</td></td>

                        <td>
                        <form action='clientemysql.php' method='post' >
                        <input type='text' name='funcion' value='eliminar' hidden />
                        <input type='text' name='id_escritor' value='".$fila['id_escritor']."' hidden />
                        <input type='submit' value='Eliminar' />
                        </form>
                        </td>

                        
                        <td>
                        <form action='clientemysql.php' method='post' >
                        <input type='update' name='funcion' value='modificar' hidden />
                        <input type='int' name='id_escritor' value='".$fila['id_escritor']."'hidden/>
                        <input type='text' name='nombre' value='".$fila['nombre']."' />
                        <input type='text' name='apellido' value='".$fila['apellido']."' />
                        <input type='text' name='direccion' value='".$fila['direccion']."' />
                        <input type='submit' value='Modificar' />
                        </form>
                        </td></tr>";
                }
                $listado = $listado."</table>";
                mysqli_close($conn);

                return  new soapval('return', 'xsd:string', $listado);

        }

    function eliminar($id){
        require_once("conexion.php");

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        $sentencia="DELETE FROM escritor WHERE id_escritor=$id ";
        //$resultado = mysqli_query($conn, $sentencia);
        if (mysqli_query($conn, $sentencia)) {
            $msg =  "Se elimino unregistro ";
        } else {
            $msg = "Error: " . $sentencia . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
    }

    function modificar($id, $nombre, $direccion, $apellido){

        require_once("conexion.php");

        $id = $_REQUEST['id_escritor'];
        $nombre = $_REQUEST['nombre'];
        $apellido = $_REQUEST['apellido'];
        $direccion = $_REQUEST['direccion'];   

        $conn = mysqli_connect($servername, $username, $password, $dbname)or die("Error de conexión con la base de datos");
        $sql = "UPDATE escritor  SET 'nombre'=$nombre, 'apellido'=$apellido, 'direccion'=$direccion,  where 'id_escritor' = $id";
       // $sql = "UPDATE escritor  set nombre='$nombre', direccion='$direccion', apellido='$apellido' where id_escritor = $id";
        if (mysqli_query($conn, $sql)) {
            $msg =  "Se introdujo una modificacion en la BD; ".$nombre;
        } else {
            $msg = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
        return new soapval('return', 'xsd:string', $msg);
}
	




    $server->register('creaContacto',
        array('nombre'=>'xsd:string','direccion'=>'xsd:string',
            'apellido'=>'xsd:string'),
        array('return'=> 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea contacto'
        );


    $server->register
    ('mostrarTodosContactos',
        array(),
        array('return' => 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea muestra los contactos'
        );
   


     $server->register
     ('buscarContacto',
        array('nombre' => 'xsd:string'),
        array('return' => 'xsd:string'),
         $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea muestra los contactos'
        );       

        $server->register
        ('eliminar',
            array('id_escritor' => 'xsd:int'),
            array('return' => 'xsd:int'),
            $namespace,
            false,
            'rpc',
            'encoded',
            'funcion que elimina usuarios'
            );

        $server->register
        ('modificar',
                array('id_escritor' => 'xsd:int','nombre' => 'xsd:string',
                'apellido' => 'xsd:string','direccion' => 'xsd:string',),
                array('return' => 'xsd:string'),
                $namespace,
                false,
                'rpc',
                'encoded',
                'funcion que modifica un usuarios'
                );
    

    if ( !isset( $HTTP_RAW_POST_DATA ) ) {
         $HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
    }

    $server->service($HTTP_RAW_POST_DATA);
?>

 

