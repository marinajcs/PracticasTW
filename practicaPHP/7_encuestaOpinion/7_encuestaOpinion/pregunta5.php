<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P5</title>
</head>

<body>
    <h1>Pregunta 5</h1>

    <?php
    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) or !isset($_COOKIE['respuesta3']) or !isset($_COOKIE['respuesta4'])){
        header('Location: pregunta1.php');
        //exit;
    }

    if (isset($_POST['respuesta5'])){
        setcookie("respuesta5", $_POST['respuesta5'], time()+1000);
        //header('Location: pregunta2.php');
    }
    ?>

     <form action="resultado.php" method='POST'>
        <label>El sistema de evaluación es:
            <input type='radio' name='respuesta5' value='ina'>
            Inadecuado
            <input type='radio' name='respuesta5' value='ade'>
            Adecuado
            <input type='radio' name='respuesta5' value='ns'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>