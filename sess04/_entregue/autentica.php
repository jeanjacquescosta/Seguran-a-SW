<?php
// ==============================
// BLOQUEAR ACESSO DIRETO
// ==============================

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["logi"], $_POST["senh"])) {
    header("Location: login.php");
    exit();
}

// ==============================
// FUNÇÃO LIMPEZA
// ==============================

function limpar_input($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
    return $dados;
}

// ==============================
// VALIDAÇÃO
// ==============================

$erros = [];

$username = limpar_input($_POST["logi"]);
$password = limpar_input($_POST["senh"]);

if (empty($username)) {
    $erros[] = "Username é obrigatório.";
}

if (empty($password)) {
    $erros[] = "Password é obrigatória.";
}

// ==============================
// AUTENTICAÇÃO
// ==============================

$conn = new mysqli("localhost", "root", "", "seguranca");

if ($conn->connect_error) {
    die("Erro de ligação: " . $conn->connect_error);
}

if (empty($erros)) {

    // Prepared statement (seguro)
    $stmt = $conn->prepare("SELECT password FROM utilizadores WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $stmt->bind_result($hash);
        $stmt->fetch();

        // Verificar password com BCrypt
        if (!password_verify($password, $hash)) {
            $erros[] = "Credenciais inválidas.";
        }

    } else {
        $erros[] = "Utilizador não existe.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>

<h2>Resultado da Autenticação BCRYPT</h2>

<?php
if (empty($erros)) {
    echo "<p style='color:green;'>Login efetuado com sucesso!</p>";
} else {
    foreach ($erros as $erro) {
        echo "<p style='color:red;'>$erro</p>";
    }
}
?>
<br>
<a href="login.php">Voltar</a>
</body>
</html>