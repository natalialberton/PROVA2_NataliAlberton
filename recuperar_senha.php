<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // VERIFICA SE O EMAIL É VÁLIDO
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // GERA UMA SENHA TEMPORÁRIA ALEATÓRIA
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // ATUALIZA A SENHA NO BANCO DE DADOS
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // SIMULA O ENVIO DO EMAIL (GRAVA EM TXT)
        simularEnvioEmail($email, $senha_temporaria);

        echo "<script> window.location.href = 'login.php';
                       alert('Uma senha temporária foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt');
              </script>";
    } else {
        echo "<script>alert('Email não encontrado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <form action="recuperar_senha.php" method="POST">
        <h2>Recuperar Senha</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
