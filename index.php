<link rel="stylesheet" href="./css/style.css">
<form action="#" method="post">

    <h3>Añadir un nuevo contacto a la agenda</h3>

    <?php 
        $nombre = " ";
        $valorNombre = "";
        $apellido1 = " ";
        $valorApellido1 = "";
        $apellido2 = " ";
        $valorApellido2 = "";
        $telefono = " ";
        $valorTelefono = "";
        $borrado = "";
        @$agenda = new mysqli('localhost', 'agenda', 'agenda', 'agenda');
        if ($agenda->connect_errno != null) {
            echo 'Error conectando a la base de datos: ';
            echo $agenda->connect_error;
            exit();
        }
        $flag = true;

        if(isset($_POST['nombre']) && $_POST['nombre'] == ""){
            $nombre = 'Error: el campo nombre está vacío';
            $valorNombre = $_POST['nombre'];
            $flag = false;
        }

        if(isset($_POST['apellido1'])&& $_POST['apellido1'] == ""){
            $apellido1 = 'Error: el campo apellido está vacío';
            $flag = false;
        }

        if(isset($_POST['apellido2'])&& $_POST['apellido2'] == ""){
            $apellido2 = 'Error: el campo apellido está vacío';
            $flag = false;
        }

        if(isset($_POST['telefono'])&& $_POST['telefono'] == ""){
            $telefono = 'Error: el campo teléfono está vacío';
            $flag = false;
        }
        
        if(isset($_POST['telefono']) && $_POST['telefono'] != ""){
            if(!preg_match("/[0-9]{9}/", $_POST['telefono'])){
                $telefono = "Introduce un teléfono válido";
                $flag = false;
            }else{
                $select = "SELECT * FROM contacto WHERE telefono='".$_POST['telefono']."';";
                $resultadoSelect = $agenda->query($select);
                if($resultadoSelect->num_rows > 0){
                    $flag = false;
                    $telefono = "El teléfono ya existe en tus contactos";
                }
                $resultadoSelect->close();
            }
        }

        if(isset($_POST['nombre'])){
            $valorNombre = $_POST['nombre'];
        }

        if(isset($_POST['apellido1'])){
            $valorApellido1 = $_POST['apellido1'];
        }

        if(isset($_POST['apellido2'])){
            $valorApellido2 = $_POST['apellido2'];
        }

        if(isset($_POST['telefono'])){
            $valorTelefono = $_POST['telefono'];
        }

        if(isset($_GET['borrado'])){
            if($_GET['borrado'] == true){
                $borrado = "El contacto ha sido borrado satisfactoriamente.";
            }else{
                $borrado = "El contacto no existe.";
            }
        }

        if(isset($_GET["id"]) && $_GET['editar'] == false){
            $id = $_GET['id'];
            $query = "SELECT * FROM contacto WHERE id='".$id."';";
            $resultado = $agenda->query($query);
            if($resultado->num_rows > 0){
                $query = "DELETE FROM contacto WHERE id='".$id."';";
                $resultado = $agenda->query($query);
                header("Location: ./index.php?borrado='true'");
                $agenda->close();
                exit();
            }else{
                header("Location: ./index.php?borrado='false'");
                $agenda->close();
            }
        }

        if((isset($_POST['boton']) && $_POST['boton'] == "Enviar") && $flag == true && (isset($_GET['editar']) && $_GET['editar'] == true)){
            $id = $_GET['id'];
            
        }else if((isset($_POST['boton']) && $_POST['boton'] == "Enviar") && $flag == true){
            $query = "INSERT INTO contacto (nombre, apellido1, apellido2, telefono) VALUES (\"".$valorNombre."\", \"".$valorApellido1."\", \"".$valorApellido2."\", \"".$valorTelefono."\");";
            $agenda->query($query);
            echo 'Formulario enviado correctamente';
        }else{
            echo ' 
                <label for="nombre">Nombre: </label> 
                <input type="text" name="nombre" id="nombre" value="' . $valorNombre . '"> '.$nombre.'<br><br>

                <label for="apellido1">Primer Apellido: </label>
                <input type="text" name="apellido1" id="apellido1" value="' . $valorApellido1 . '">'.$apellido1.'<br><br>

                <label for="apellido2">Segundo Apellido: </label>
                <input type="text" name="apellido2" id="apellido2" value="' . $valorApellido2 . '">'.$apellido2.'<br><br>

                <label for="telefono">Número de Teléfono: </label>
                <input type="text" name="telefono" id="telefono" value="' . $valorTelefono . '">'.$telefono.'<br><br>

                <input type="submit" value="Enviar" name="boton"></input><br><br><br>
                '.$borrado.'<br><br>
            </form>
            ';
            $query = "SELECT * FROM contacto";
            $resultado = $agenda->query($query, MYSQLI_USE_RESULT);
            if($resultado->num_rows < 0){
                echo 'No existen contactos en la agenda.';
            }else{
                echo '<b>Lista de Contactos</b><br><br>';
                echo '<table><tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Editar</th>
                        <th>Borrar</th>
                        </tr>';
                while($contactos = $resultado->fetch_row()){
                    echo "<tr><td>".$contactos[1]."</td>
                        <td>".$contactos[2]." ".$contactos[3]."</td>
                        <td>".$contactos[4]."</td>
                        <td><div><a href='index.php?id=".$contactos[0]."?editar=true'>
                        <img src='./img/editar.png'></div></td>
                        <td><div><a href='index.php?id=".$contactos[0]."?editar=false'>
                        <img src='./img/papelera.png'></div></td></tr>";
                }
            }
            
            $agenda->close();
        }
?>
