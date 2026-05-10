<?php

// ======================================
// LIGAÇÃO AO MYSQL
// ======================================

$conn = new mysqli(
    "localhost",
    "root",
    ""
);

// Verificar ligação
if ($conn->connect_error) {
    die("Erro de ligação: " . $conn->connect_error);
}


// ======================================
// CRIAR BASE DE DADOS
// ======================================

$conn->query(
    "CREATE DATABASE IF NOT EXISTS seguranca_final"
);


// Selecionar BD
$conn->select_db("seguranca_final");


// ======================================
// CRIAR TABELA
// ======================================

$sql = "
CREATE TABLE IF NOT EXISTS utilizadores (

    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    secret_mfa VARCHAR(255) NOT NULL

)
";

$conn->query($sql);


// ======================================
// APAGAR PROCEDURE ANTIGA
// ======================================

$conn->query(
    "DROP PROCEDURE IF EXISTS login_user"
);


// ======================================
// CRIAR PROCEDURE
// ======================================

$procedure = "

CREATE PROCEDURE login_user(IN p_user VARCHAR(50))

BEGIN

    SELECT *
    FROM utilizadores
    WHERE username = p_user;

END

";

$conn->query($procedure);


// ======================================
// PASSWORDS BCRYPT
// ======================================

$pass1 = password_hash(
    "formandoJJ1!",
    PASSWORD_BCRYPT
);

$pass2 = password_hash(
    "formadorJO2!",
    PASSWORD_BCRYPT
);

$pass3 = password_hash(
    "visitanteVI3!",
    PASSWORD_BCRYPT
);


// ======================================
// MFA SECRETS
// ======================================

$secret1 = "JBSWY3DPEHPK3PXP";
$secret2 = "KRSXG5DSMFZWI2LT";
$secret3 = "NB2W45DFOIZA====";


// ======================================
// INSERIR UTILIZADORES
// ======================================

$stmt = $conn->prepare(

"INSERT IGNORE INTO utilizadores
(username,password,secret_mfa)

VALUES (?, ?, ?)"

);

$stmt->bind_param(
    "sss",
    $u,
    $p,
    $s
);


// Utilizador 1
$u = "jeanjacques";
$p = $pass1;
$s = $secret1;
$stmt->execute();


// Utilizador 2
$u = "joao";
$p = $pass2;
$s = $secret2;
$stmt->execute();


// Utilizador 3
$u = "visita";
$p = $pass3;
$s = $secret3;
$stmt->execute();


// ======================================
// SUCESSO
// ======================================

echo "Sistema criado com sucesso!";


// Fechar ligação
$conn->close();

?>