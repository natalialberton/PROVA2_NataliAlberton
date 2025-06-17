<?php
session_start();
require '../conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    echo "<script>alert('Acesso negado!'); 
          window.location.href='../principal.php';</script>";
    exit();
}

$fornecedores = [];

$sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fornecedor = $_GET['id'];

    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor excluído com sucesso!'); 
              window.location.href='excluir_fornecedor.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir fornecedor!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>EXCLUIR FORNECEDOR</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="container">
        <header class="container__titulo">
            <h2>Excluir Fornecedor</h2>
        </header>
        <div class="container-tabela">
        <?php if (!empty($fornecedores)): ?>
            <table class="container-tabela__tabela">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Contato</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($fornecedores as $fornecedor): ?>
                    <tr>
                        <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                        <td><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></td>
                        <td><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                        <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                        <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                        <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                        <td>
                            <div class="tabela__btn">
                                <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>" 
                                class="btn-acao btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="container-tabela__sem-resultado">
                <div class="container-tabela__sem-resultado__icon">📦</div>
                <p>Nenhum fornecedor encontrado.</p>
            </div>
        <?php endif; ?>
    </div>
        <a href="../principal.php" class="btn-voltar">Voltar</a>
    </main>
    <footer> Desenvolvido por Natalí Alberton Grolli - SENAI</footer>
</body>
</html>