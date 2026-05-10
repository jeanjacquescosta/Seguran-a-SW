<?php include_once "segur_cabecalho.php"; ?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>LOGIN SEGURO</title>
</head>
<body>

<h2>Autenticação Segura</h2>

<form method="post" action="autentica.php">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    
    Username:
    <input type="text"
           name="logi"
           required
           autocomplete="username"
           maxlength="50"
           pattern="[a-zA-Z0-9_]+">

    <br><br>

    Password:
    <input type="password"
           name="senh"
           autocomplete="current-password"
           required>

    <br><br>

    Código MFA:
    <input type="text"
           name="codigo"
           required
           autocomplete="one-time-code"
           maxlength="6"
           pattern="[0-9]{6}">

    <br><br>

    <input type="submit" value="Entrar">

</form>

</body>
</html>