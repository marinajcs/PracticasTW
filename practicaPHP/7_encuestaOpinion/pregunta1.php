
<?php
    $respuestas = array('respuesta1', 'respuesta2', 'respuesta3', 'respuesta4', 'respuesta5');
    foreach ($respuestas as $cookie){
        if (isset($_COOKIE[$cookie])){
            setcookie('$cookie', '', time()-3600);
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P1</title>
</head>

<body>
    <h1>Pregunta 1</h1>

     <form action="pregunta2.php" method='POST'>
        <label>¿Tenía conocimientos previos de la materia explicada?
            <input type='radio' name='respuesta1' value='Sí'>
            Sí
            <input type='radio' name='respuesta1' value='No'>
            No
            <input type='radio' name='respuesta1' value='No sabe no contesta'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>