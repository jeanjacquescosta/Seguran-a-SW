<?php
include_once "segur_cabecalho.php";
if (!isset($_SESSION["user"])) {

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Área Reservada</title>
</head>
<body>

<h2>Área Reservada</h2>

<p>
Bem-vindo,
<?php
    echo htmlspecialchars(
    $_SESSION["user"],
    ENT_QUOTES,
    'UTF-8'
);
?>
</p>

<br>

<a href="logout.php">
Logout
</a>

</body>
</html>