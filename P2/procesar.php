<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Variables recibidas</title>
    <style type="text/css">
      .valor { color: red; }
    </style>
  </head>
<body>
  <?php echo "<h1>Variables recibidas</h1>";
      showVar($_GET,'Desde $_GET');
      showVar($_POST,'Desde $_POST');
      function showVar($var,$msg) {
        echo "<h2>$msg</h2>";
        echo "<ul>";
        foreach ($var as $c => $v) {
          if (is_array($v)) {
            echo "<li>$c = <span class='valor'>"; 
            print_r($v); 
            echo "</span></li>";
          } else
            echo "<li>$c = <span class='valor'>$v</span></li>";
        }
        echo "</ul>";        
      }
  ?>
</body>
</html>
