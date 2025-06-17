<?php
session_start();
require '../conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa variável para armazenar usuários
$usuarios = [];

// Busca todos os usuários cadastrados em ordem alfabética
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um ID for passado via GET, exclui o usuário
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Exclui o usuário do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='excluir_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir usuário!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="container">
        <header class="container__titulo">
            <h2>Excluir Usuário</h2>
        </header>

        <div class="container-tabela">
            <?php if (!empty($usuarios)): ?>
                <table class="container-tabela__tabela">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Ações</th>
                    </tr>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= htmlspecialchars($usuario['id_perfil']) ?></td>
                            <td>
                                <div class="tabela__btn">
                                    <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>"  class="btn-acao btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <div class="container-tabela__sem-resultado">
                    <div class="container-tabela__sem-resultado__icon">🤷‍♀️</div>
                    <p>Nenhum usuário encontrado.</p>
                </div>
            <?php endif; ?>
    </div>
        <a href="../principal.php" class="btn-voltar">Voltar</a>
    </main>
    <footer> Desenvolvido por Natalí Alberton Grolli - SENAI</footer>
</body>
</html>