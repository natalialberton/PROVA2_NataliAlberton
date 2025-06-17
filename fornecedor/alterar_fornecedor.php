<?php
session_start();
require '../conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] === 4) {
    echo "<script>alert('Acesso negado!');
          window.location.href='../principal.php';</script>";
    exit();
}

// Inicializa variáveis
$fornecedor = null;

// Se o formulário for enviado, busca o usuário pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //ADICIONADA VALIDAÇÃO PARA REALIZAR ALTERAÇÃO DE DADOS
    if (isset($_POST['form_id'])) {
        if ($_POST['form_id'] === 'form_busca') {
            if (!empty($_POST['busca_fornecedor'])) {
                $busca = trim($_POST['busca_fornecedor']);

                // Verifica se a busca é um número (ID) ou um nome
                if (is_numeric($busca)) {
                    $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
                } else {
                    $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
                }

                $stmt->execute();
                $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$fornecedor) {
                    echo "<script>alert('Fornecedor não encontrado!');</script>";
                }
            }
        } else {
            $id_fornecedor = $_POST['id_fornecedor'];
            $nome_fornecedor = $_POST['nome_fornecedor'];
            $endereco = $_POST['endereco'];
            $telefone = $_POST['telefone'];
            $email = $_POST['email'];
            $contato = $_POST['contato'];

            $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, endereco = :endereco, telefone = :telefone,
                    email = :email, contato = :contato WHERE id_fornecedor = :id_fornecedor";
            $stmt = $pdo-> prepare($sql);
            $stmt-> bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
            $stmt-> bindParam(':nome_fornecedor', $nome_fornecedor);
            $stmt-> bindParam(':endereco', $endereco);
            $stmt-> bindParam(':telefone', $telefone);
            $stmt-> bindParam(':email', $email);
            $stmt-> bindParam(':contato', $contato);

            if ($stmt-> execute()) {
                echo "<script>alert('Fornecedor alterado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao alterar fornecedor!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ALTERAÇÃO FORNECEDOR</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js"></script>
    <script src="../validacoes.js"></script>
</head>
<body>
    <main class="container">
        <header class="container__titulo">
            <h2>Alterar Fornecedor</h2>
        </header>

        <!-- Formulário para buscar usuário pelo ID ou Nome -->
        <form action="alterar_fornecedor.php" method="POST">
            <input type="hidden" name="form_id" value="form_busca">
            <label for="busca_fornecedor">Digite o ID ou Nome do fornecedor:</label>
            <input type="text" id="busca_fornecedor" name="busca_fornecedor" required onkeyup="buscarSugestoes()">
            
            <!-- Div para exibir sugestões de usuários -->
            <div id="sugestoes"></div>
            
            <button type="submit">Buscar</button>
        </form>

        <?php if ($fornecedor): ?>
            <!-- Formulário para alterar usuário -->
            <form action="alterar_fornecedor.php" method="POST">
                <input type="hidden" name="form_id" value="form_altera">
                <input type="hidden" name="id_fornecedor" value="<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>">

                <label for="nome_fornecedor">Nome:</label>
                <input type="text" id="nome_fornecedor" name="nome_fornecedor" onkeypress="mascara(this, nomeMasc)" minlength="3"
                    value="<?= htmlspecialchars($fornecedor['nome_fornecedor']) ?>" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($fornecedor['endereco']) ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" onkeypress="mascara(this, telefoneMasc)" maxlength="15"
                    value="<?= htmlspecialchars($fornecedor['telefone']) ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($fornecedor['email']) ?>" required>

                <label for="contato">Contato:</label>
                <input type="text" id="contato" name="contato" value="<?= htmlspecialchars($fornecedor['contato']) ?>" required>

                <button type="submit">Alterar</button>
                <button type="reset">Cancelar</button>
            </form>
        <?php endif; ?>
        <a href="../principal.php" class="btn-voltar">Voltar</a>
    </main>
    <footer> Desenvolvido por Natalí Alberton Grolli - SENAI</footer>
</body>
</html>