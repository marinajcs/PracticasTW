<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P4</title>
</head>

<body>
    <h1>Pregunta 4</h1>

    <?php
    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) or !isset($_COOKIE['respuesta3'])){
        header('Location: pregunta1.php');
        //exit;
    }

    if (isset($_POST['respuesta4'])){
        setcookie("respuesta4", $_POST['respuesta4'], time()+1000);
        //header('Location: pregunta2.php');
    }
    ?>

     <form action="pregunta5.php" method='POST'>
        <label>La coordinación entre teoría y prácticas es:
            <input type='radio' name='respuesta4' value='def'>
            Mala
            <input type='radio' name='respuesta4' value='ade'>
            Buena
            <input type='radio' name='respuesta4' value='ns'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>