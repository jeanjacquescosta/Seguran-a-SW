<?php
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["logi"], $_POST["senh"])) {           // Acesso direto ou dados em falta
    header("Location: login.php");
    exit();
}
function limpar_input($dados) {
    $dados = trim($dados);                                                                              // remove espaços
    $dados = stripslashes($dados);                                                                      // remove barras
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');                                             // evita XSS
    return $dados;
}
$erros = [];
$username = "";
$password = "";
if (empty($_POST["logi"])) {                                                                        // Validação do nome deutilizador
    $erros[] = "LOGIN é obrigatório.";
} else {
    $username = limpar_input($_POST["logi"]);                                                       // Limpeza do nome de utilizador
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $erros[] = "LOGIN contém caracteres inválidos.";
    }
}
if (empty($_POST["senh"])) {                                                                        // Validação da senha 
    $erros[] = "SENHA é obrigatória.";
} else {
    $password = limpar_input($_POST["senh"]);
}
$conn = new mysqli("localhost", "root", "", "seguranca");                                               // Ligação à base de dados
if ($conn->connect_error) {
    die("Erro de ligação a BD: " . $conn->connect_error);
}
$resultado = null;
if (empty($erros)) {                                                                                    
    $conn->query("SET @user = '".$username."'");
    $conn->query("SET @pass = '".$password."'");
    $resultado = $conn->query("CALL login_user(@user, @pass)");                                         // Chamada a procedure de login
    if (!$resultado || $resultado->num_rows == 0) {                                                     // Verificação do resultado   
        $erros[] = "AUTENTICAÇÃO inválida.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultado de AUTENTICAÇÃO</title>
</head>
<body>
<h2>Resultado</h2>
<?php
if (empty($erros)) {
    echo "<p style='color:green;'>AUTENTICAÇÃO efetuada com sucesso!</p>";
} else {
    foreach ($erros as $erro) {
        echo "<p style='color:red;'>$erro</p>";
    }
}
?>
<br>
<a href="login.php">Voltar a AUTENTICAÇÃO</a>
</body>
</html>
<?php
$conn->close();
?>