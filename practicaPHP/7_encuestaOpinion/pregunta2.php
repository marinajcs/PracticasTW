<?php
    setcookie('respuesta1', $_POST['respuesta1'], time()+3600);

    if (!isset($_POST['respuesta1'])){
        header('Location: pregunta1.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P2</title>
</head>

<body>
    <h1>Pregunta 2</h1>

     <form action="pregunta3.php" method='POST'>
        <label>Considera que la profundidad del temario en estos temas es:
            <input type='radio' name='respuesta2' value='Deficiente'>
            Deficiente
            <input type='radio' name='respuesta2' value='Adecuada'>
            Adecuada
            <input type='radio' name='respuesta2' value='Excesiva'>
            Excesiva
            <input type='radio' name='respuesta2' value='No sabe no contesta'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>