<?php
session_start();
require_once '../conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO; SE É ADMIN (1)
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); 
          window.location.href='../principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];

    $sql = "INSERT INTO usuario(nome, email, senha, id_perfil) VALUES(:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo-> prepare($sql);
    $stmt-> bindParam(':nome', $nome);
    $stmt-> bindParam(':email', $email);
    $stmt-> bindParam(':senha', $senha);
    $stmt-> bindParam(':id_perfil', $id_perfil);

    if ($stmt-> execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuário!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CADASTRO DE USUÁRIO</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="container">
        <header class="container__titulo">
            <h2>Cadastrar Usuário</h2>
        </header>

        <form action="cadastrar_usuario.php" method="POST">
            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" placeholder="Digite seu nome" onkeypress="mascara(this, nomeMasc)" minlength="3" required>

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" placeholder="Digite seu email" required>

            <label for="senha">Senha: </label>
            <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>

            <label for="id_perfil">Perfil: </label>
            <select name="id_perfil" id="id_perfil">
                <option value="1">Administrador</option>
                <option value="2">Secretária</option>
                <option value="3">Almoxarife</option>
                <option value="4">Cliente</option>
            </select>

            <button type="submit">Salvar</button>
            <button type="reset">Cancelar</button>
        </form>

        <a href="../principal.php" class="btn-voltar">Voltar</a>
    </main>
    <footer> Desenvolvido por Natalí Alberton Grolli - SENAI</footer>
</body>
</html>