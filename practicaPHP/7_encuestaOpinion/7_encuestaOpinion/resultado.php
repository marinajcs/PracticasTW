<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - Resultado</title>
</head>

<body>
    <h1>Resultado encuesta</h1>

    <?php
    if (!isset($_COOKIE['respuesta1']) or !isset($_COOKIE['respuesta2']) or !isset($_COOKIE['respuesta3']) or
        !isset($_COOKIE['respuesta4']) or !isset($_COOKIE['respuesta5'])) {
        header('Location: pregunta1.php');
        //exit;
    }
    ?>

    <ol>
        <li>¿Tenía conocimientos previos de la materia explicada?</li>
        <?php $_COOKIE['respuesta1']; ?>
        <li>Considera que la profundidad del temario en estos temas es:</li>
        <?php $_COOKIE['respuesta2']; ?>
        <li>Las explicaciones de teoría son:</li>
        <?php $_COOKIE['respuesta3']; ?>
        <li>La coordinación entre teoría y prácticas es:</li>
        <?php $_COOKIE['respuesta4']; ?>
        <li>El sistema de evaluación es:</li>
        <?php $_COOKIE['respuesta5']; ?>
    </ol>

</body>

</html>