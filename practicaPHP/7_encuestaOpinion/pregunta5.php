<?php
    setcookie("respuesta4", $_POST['respuesta4'], time()+3600);

    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) or !isset($_COOKIE['respuesta3']) and !isset($_POST['respuesta4'])){
        header('Location: pregunta1.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P5</title>
</head>

<body>
    <h1>Pregunta 5</h1>

     <form action="resultado.php" method='POST'>
        <label>El sistema de evaluación es:
            <input type='radio' name='respuesta5' value='Inadecuado'>
            Inadecuado
            <input type='radio' name='respuesta5' value='Adecuado'>
            Adecuado
            <input type='radio' name='respuesta5' value='No sabe no contesta'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>