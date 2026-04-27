<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Inserir Dados</title>
</head>
<body>

<h2>Formulário</h2>

<form method="post" action="mostra.php">
    
    Nome: <input type="text" name="nome" required maxlength="60"><br><br>
    
    Idade: <input type="number" name="idade" required min="0" max="130"><br><br>
    
    Género:<br>
    <input type="radio" name="genero" value="m" checked> Homem<br>
    <input type="radio" name="genero" value="f"> Mulher<br><br>
    
    <input type="submit" value="Enviar">
</form>

</body>
</html>