<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 6</title>
</head>

<body>

    <?php
    $msj = json_decode(file_get_contents('mensajes.json'),true);
    $enviado = (isset($_POST['enviar']));
    $idioma_act = "es";

    if($enviado == true){
        $idioma_act = $_POST['idioma_opt'];
        setcookie("idioma", $idioma_act, time()+30*24*60*60);
    } else if (isset($_COOKIE['idioma'])) {
        $idioma_act = $_COOKIE["idioma"];
    } else {
        $idioma_act = "es";
    }

    $welcome = $msj[$idioma_act]["Bienvenida"];
    $note = $msj[$idioma_act]["Cambio"];
    $choose = $msj[$idioma_act]["ElegirIdioma"];
    $esp = $msj[$idioma_act]["Espanol"];
    $eng = $msj[$idioma_act]["Ingles"];
    $fre = $msj[$idioma_act]["Frances"];
    $send = $msj[$idioma_act]["Aplicar"];
    $link = $msj[$idioma_act]["Enlace"];

    echo "<p>$welcome</p>";
    echo "<p>$note</p>";
    echo "<fieldset>
        <legend>$choose</legend>
        <form action='".$_SERVER["SCRIPT_NAME"]."' method='POST'>
            <select name='idioma_opt'>
                <option value='es'"; 
                if ($idioma_act == "es") echo " selected ";
                echo ">$esp</option>";
                echo "<option value='en'"; 
                if ($idioma_act == "en") echo " selected ";
                echo ">$eng</option>";
                echo "<option value='fr'"; 
                if ($idioma_act == "fr") echo " selected ";
                echo ">$fre</option>";
            echo "</select>
            <input type='submit' name='enviar' value='$send'>
        </form>
    </fieldset>";
    echo "<p><a href='" . $_SERVER["SCRIPT_NAME"] . "'>$link</a></p>";
    ?>
</body>

</html>