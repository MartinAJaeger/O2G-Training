<!DOCTYPE html>
<!-- File index.php -->
<html>
<head>
   <title>Hello World Apache/PHP</title>
   <meta charset="utf-8"/>
</head>
<body>
<h1>Hello World: Apache/PHP</h1>
<?php
   $load = sys_getloadavg();
?>
   The running PHP version is: <?php echo  phpversion(); ?><br />
   Servertime: <?php echo date("c"); ?><br />
   Serverload: <?php echo $load[0]; ?>
</body>
</html>
