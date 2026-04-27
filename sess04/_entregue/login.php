<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Seguro</title>
</head>
<body>

<h2>Autenticação</h2>

<form method="post" action="autentica.php">
    Username:
    <input type="text" name="logi" required><br>
    Password:
    <input type="password" name="senh" required><br>
    <input type="submit" value="Entrar">
</form>
<hr>
<a href="registo.php">Criar conta</a>
</body>
</html>