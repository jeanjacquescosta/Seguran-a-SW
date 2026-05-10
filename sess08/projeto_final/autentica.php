<?php
include_once "segur_cabecalho.php";

if (
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("Erro CSRF: pedido inválido.");
}


// Impedir acesso direto
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

// Biblioteca MFA
require_once "vendor/PHPGangsta/GoogleAuthenticator.php";

// Ligação BD
require_once "bd.php";

// Função de limpeza
function limpar_input($dados) {

    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');

    return $dados;
}

$erros = [];


// ==============================
// VALIDAR LOGIN
// ==============================

if (empty($_POST["logi"])) {

    $erros[] = "LOGIN obrigatório.";

} else {

    $username = limpar_input($_POST["logi"]);

    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {

        $erros[] = "LOGIN inválido.";

    }
}


// ==============================
// VALIDAR PASSWORD
// ==============================

if (empty($_POST["senh"])) {

    $erros[] = "PASSWORD obrigatória.";

} else {

    $password = limpar_input($_POST["senh"]);

}


// ==============================
// VALIDAR MFA
// ==============================

if (empty($_POST["codigo"])) {

    $erros[] = "Código MFA obrigatório.";

} else {

    $codigo = limpar_input($_POST["codigo"]);

}


// ==============================
// AUTENTICAÇÃO
// ==============================

if (empty($erros)) {

    // Procedure SQL
    $stmt = $conn->prepare("CALL login_user(?)");

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $resultado = $stmt->get_result();


    // Utilizador existe?
    if ($resultado->num_rows == 1) {

        $row = $resultado->fetch_assoc();


        // Verificar password BCrypt
        if (password_verify($password, $row["password"])) {

            // MFA
            $ga = new PHPGangsta_GoogleAuthenticator();

            $secret = $row["secret_mfa"];

            $checkResult = $ga->verifyCode(
                $secret,
                $codigo,
                2
            );

            // MFA válido?
            if ($checkResult) {

                session_regenerate_id(true);

                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                $_SESSION["user"] = $username;

                header("Location: admin.php");

                exit();

            } else {

                $erros[] = "Código MFA inválido.";

            }

        } else {

            $erros[] = "Password incorreta.";

        }

    } else {

        $erros[] = "Utilizador inexistente.";

    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>

<h2>Resultado da Autenticação</h2>

<?php

if (!empty($erros)) {

    foreach ($erros as $erro) {

        echo "<p style='color:red;'>$erro</p>";

    }
}

?>

<br>

<a href="login.php">Voltar</a>

</body>
</html>