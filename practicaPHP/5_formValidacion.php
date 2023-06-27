<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Ejercicio 5</title>
    <style>
        label {
            display: block;
        }

        form, h1 {
            background-color: bisque;
        }

        .error {
            color: red;
        }

    </style>
</head>

<body>
    <?php

    $nombre = '';
    $email = '';
    $telefono = '';
    $edad = '';
    $hobbies = array();
    $err['nombre'] = '';
    $err['email'] = '';
    $err['telefono'] = '';
    $err['edad'] = '';

    $recibido = false;
    $enviado = isset($_POST['enviar']);

    function validarDatos($nom, $ema, $tel, $eda){
        $err['nombre'] = '';
        $err['email'] = '';
        $err['telefono'] = '';
        $err['edad'] = '';

        if (!isset($nom) or $nom == '')
            $err['nombre'] = 'Debe escribir su nombre';

        if (!filter_var($ema, FILTER_VALIDATE_EMAIL))
            $err['email'] = 'El formato del email es inválido';
        else if ($ema == '')
            $err['email'] = 'Debe escribir su email';


        if (preg_match('/^\d{9}$/', $tel) == false)
            $err['telefono'] = 'El formato del teléfono es incorrecto';
        else if ($tel == '')
            $err['telefono'] = 'Debe escribir su teléfono';

        if (!isset($eda) or empty($eda)){
            $err['edad'] = 'Debe marcar su edad';
        }

        return $err;
    }

    if (isset($_POST['enviar'])) {

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        if (isset($_POST['edad']))
            $edad = $_POST['edad'];

        if (isset($_POST['hobbies']))
            $hobbies = $_POST['hobbies'];

        $err = validarDatos($nombre, $email, $telefono, $edad);

        if ($err['nombre'] == '' and $err['email'] == '' and $err['telefono'] == '' and $err['edad'] == '')
            $recibido = true;

    }

    if ($recibido) {
        echo "<h1>Datos recibidos correctamente</h1>
                <p>Hola <strong>$nombre</strong>, a continuación se muestra el 
                formulario con los datos. En el modo visualización, la entrada 
                de datos y opción de envío están deshabilitadas.</p>";
    }

    showForm($nombre, $email, $telefono, $edad, $hobbies, $err, $enviado, $recibido);

    function showForm($nom, $ema, $tel, $eda, $hob, $err, $env, $rec){
        echo "<h1>Formulario:</h1>

        <form action='".$_SERVER["SCRIPT_NAME"]."' method='POST' novalidate>
            <label>Nombre:
                <input type='text' name='nombre'";
                if ($err['nombre'] == '') 
                    echo " value='$nom'";
                echo " placeholder='Escriba su nombre'";
                if ($rec) echo " disabled";
                echo ">
            </label>";
            if ($err['nombre'] != '')
                echo "<p class='error'>{$err['nombre']}</p>";

            echo "
            <label>Email:
                <input type='text' name='email'";
                if ($err['email'] == '') echo " value='$ema'";
                echo " placeholder='Escriba su email'";
                if ($rec) echo " disabled";
                echo ">
            </label>";
            if ($err['email'] != '')
                echo "<p class='error'>{$err['email']}</p>";

            echo "
            <label>Teléfono:
                <input type='text' name='telefono'";
                if ($err['telefono'] == '') echo " value='$tel'";
                echo " placeholder='Escriba su teléfono'";
                if ($rec) echo " disabled";
                echo ">
            </label>";
            if ($err['telefono'] != '')
                echo "<p class='error'>{$err['telefono']}</p>";

            echo "
            <fieldset>
                <legend>Edad</legend>
                    <input type='radio' name='edad' value='menor'";
                    if ($eda == 'menor') echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Menores de 12 años

                    <input type='radio' name='edad' value='adolescente'";
                    if ($eda == 'adolescente') echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Entre 12 y 18 años

                    <input type='radio' name='edad' value='adulto'";
                    if ($eda == 'adulto') echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Mayor de 18 años";
            if ($err['edad'] != '')
                echo "<p class='error'>{$err['edad']}</p>";
            echo "
            </fieldset>

            <fieldset>
                <legend>Aficiones</legend>
                    <input type='checkbox' name='hobbies[]' value='pajaros'";
                    if ($hob != null and in_array('pajaros',$hob)) echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Los pájaros

                    <input type='checkbox' name='hobbies[]' value='videojuegos'";
                    if ($hob != null and in_array('videojuegos',$hob)) echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Los videojuegos

                    <input type='checkbox' name='hobbies[]' value='botones'";
                    if ($hob != null and in_array('botones',$hob)) echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Coleccionar botones de camisas

                    <input type='checkbox' name='hobbies[]' value='techo'";
                    if ($hob != null and in_array('techo',$hob)) echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Mirar el techo

                    <input type='checkbox' name='hobbies[]' value='programar'";
                    if ($hob != null and in_array('programar',$hob)) echo " checked";
                    if ($rec) echo " disabled";
                    echo ">Programar en ensamblador
            </fieldset>

            <input type='submit' name='enviar' value='Enviar datos'";
            if ($rec) echo " disabled";
            echo ">
        </form>";
    }

    ?>
    
</body>

</html>
