<?php
    setcookie("respuesta3", $_POST['respuesta3'], time()+3600);

    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) and !isset($_POST['respuesta3'])){
        header('Location: pregunta1.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P4</title>
</head>

<body>
    <h1>Pregunta 4</h1>

     <form action="pregunta5.php" method='POST'>
        <label>La coordinación entre teoría y prácticas es:
            <input type='radio' name='respuesta4' value='Mala'>
            Mala
            <input type='radio' name='respuesta4' value='Buena'>
            Buena
            <input type='radio' name='respuesta4' value='No sabe no contesta'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>