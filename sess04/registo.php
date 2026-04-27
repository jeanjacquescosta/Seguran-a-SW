<?php
    include_once("registo_bcrypt.php");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo Seguro BCRYPT</title>
</head>
<body>
<h2>Registo</h2>
<form method="post">
    Username: <input type="text" name="logi" required><br>
    Password: <input type="password" name="senh" required><br>
    Confirmar Password: <input type="password" name="conf_senh" required><br>
    <input type="submit" value="Registar">
</form>
<?php
    include_once("_erros.php");
?>
<br>
<a href="login.php">Ir para Login</a>
</body>
</html>