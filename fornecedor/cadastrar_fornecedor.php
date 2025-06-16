<?php
session_start();
require_once '../conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO; SE É ADMIN (1)
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    header("Location: ../principal.php");
    echo "<script>alert('Acesso negado!');</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $contato = $_POST['contato'];
    $id_funcionario_registro = $_SESSION['id_usuario'];

    $sql = "INSERT INTO fornecedor(nome_fornecedor, endereco, telefone, email, contato, id_funcionario_registro) 
    VALUES(:nome_fornecedor, :endereco, :telefone, :email, :contato, :id_funcionario_registro)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contato', $contato);
    $stmt->bindParam(':id_funcionario_registro', $id_funcionario_registro);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar fornecedor!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CADASTRO DE FORNECEDOR</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../validacoes.js"></script>

    <style>
        /* Ajusta os campos de entrada */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select {
            width: 80%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h2>Cadastrar Fornecedor</h2>

    <form action="cadastrar_fornecedor.php" method="POST">
        <label for="nome_fornecedor">Nome do Fornecedor: </label>
        <input type="text" name="nome_fornecedor" id="nome_fornecedor" placeholder="Digite seu nome" 
               onkeypress="mascara(this, nomeMasc)" minlength="3" required>

        <label for="endereco">Endereço: </label>
        <input type="text" name="endereco" id="endereco" placeholder="Digite seu endereco" required>

        <label for="telefone">Telefone: </label>
        <input type="tel" name="telefone" id="telefone" placeholder="Digite seu telefone" 
               onkeypress="mascara(this, telefoneMasc)" maxlength="15" required>

        <label for="email">Email: </label>
        <input type="email" name="email" id="email" placeholder="Digite seu email" required>

        <label for="contato">Contato: </label>
        <input type="text" name="contato" id="contato" placeholder="Digite seu contato" required>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <a href="../principal.php">Voltar</a>
</body>

</html>