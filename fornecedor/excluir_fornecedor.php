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
    <h2>Excluir Fornecedor</h2>

    <?php if (!empty($fornecedores)): ?>
        <table>
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
                        <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum fornecedor encontrado.</p>
    <?php endif; ?>

    <a href="../principal.php">Voltar</a>
</body>
</html>