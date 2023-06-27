<?php
    setcookie("respuesta2", $_POST['respuesta2'], time()+3600);
    
    if (!isset($_COOKIE['respuesta1']) and !isset($_POST['respuesta2'])){
        header('Location: pregunta1.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ejercicio 7 - P3</title>
</head>

<body>
    <h1>Pregunta 3</h1>

     <form action="pregunta4.php" method='POST'>
        <label>Las explicaciones de teoría son:
            <input type='radio' name='respuesta3' value='Malas'>
            Malas
            <input type='radio' name='respuesta3' value='Normales'>
            Normales
            <input type='radio' name='respuesta3' value='Buenas'>
            Buenas
            <input type='radio' name='respuesta3' value='No sabe no contesta'>
            NS/NC
        </label>
        <input type='submit' name='siguiente' value='Siguiente'>
    </form>

</body>

</html>