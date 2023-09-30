<?php
        require_once("nusoap.php");
        $namespace = "http://localhost/mysql";
        $serverScript = 'servermysql.php';
        $metodoALlamar = $_POST['funcion'];
        $client = new nusoap_client("$namespace/$serverScript?wsdl", 'wsdl');

        if($metodoALlamar == 'creaContacto') {
            $result = $client->call(
                "$metodoALlamar", 
                array('nombre' => $_POST['nombre'],
                        'direccion' => $_POST['direccion'],
                        'apellido' => $_POST['apellido']),
                "uri:$namespace/$serverScript", 
                "uri:$namespace/$serverScript/$metodoALlamar" 
            );
        } 
        if($metodoALlamar == 'buscarContacto') {
        //  echo $_POST['nombre'].$metodoALlamar;
            $result = $client->call(
                "$metodoALlamar", 
                array('nombre' => $_POST['nombre']), 
                "uri:$namespace/$serverScript", 
                "uri:$namespace/$serverScript/$metodoALlamar" 
            );
        } 
        if($metodoALlamar == 'mostrarTodosContactos') {  
            $result = $client->call(
                "$metodoALlamar", 
                array(), 
                "uri:$namespace/$serverScript", 
                "uri:$namespace/$serverScript/$metodoALlamar" 
            );
        }
        if($metodoALlamar == 'eliminar') {
                $result = $client->call(
                    "$metodoALlamar", 
                    array('id_escritor' => $_POST['id_escritor']), 
                    "uri:$namespace/$serverScript", 
                    "uri:$namespace/$serverScript/$metodoALlamar" 
                );
            } 

            if($metodoALlamar == 'modificar') {
                $result = $client->call(
                    "$metodoALlamar", 
                    array( 'id_escritor' => $_POST['id_escritor'],
                            'nombre' => $_POST['nombre'],
                            'direccion' => $_POST['direccion'],
                            'apellido' => $_POST['apellido']),
                    "uri:$namespace/$serverScript", 
                    "uri:$namespace/$serverScript/$metodoALlamar" 
                );
            } 

        echo $result."<br><br><a href='crud.html'>Volver a formulario</a>";
?>