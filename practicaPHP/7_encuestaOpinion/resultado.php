<?php
    setcookie("respuesta5", $_POST['respuesta5'], time()+3600);

    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) or !isset($_COOKIE['respuesta3'])
         or !isset($_COOKIE['respuesta4']) and !isset($_POST['respuesta5'])) {
        header('Location: pregunta1.php');
        exit();
    }

    if (!isset($_COOKIE['respuesta5'])){
        $resp5 = $_POST['respuesta5'];
    } else {
        $resp5 = $_COOKIE['respuesta5'];
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - Resultado</title>
</head>

<body>
    <h1>Resultado encuesta</h1>

    <ol>
        <li>¿Tenía conocimientos previos de la materia explicada?</li>
        <?php echo $_COOKIE['respuesta1']; ?>
        <li>Considera que la profundidad del temario en estos temas es:</li>
        <?php echo $_COOKIE['respuesta2']; ?>
        <li>Las explicaciones de teoría son:</li>
        <?php echo $_COOKIE['respuesta3']; ?>
        <li>La coordinación entre teoría y prácticas es:</li>
        <?php echo $_COOKIE['respuesta4']; ?>
        <li>El sistema de evaluación es:</li>
        <?php echo $resp5; ?>
    </ol>

</body>

</html>