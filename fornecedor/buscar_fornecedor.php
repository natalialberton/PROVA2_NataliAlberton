<?php
session_start();
require_once '../conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO
if ($_SESSION['perfil'] === 4) {
    header("Location: ../principal.php");
    echo "<script>alert('Acesso negado!');</script>";
    exit();
}

//INICIALIZA A VARIÁVEL, PARA EVITAR ERROS
$fornecedores = [];

//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO PELO ID OU PELO NOME
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    //VERIFICA SE A BUSCA É UM NÚMERO (id) OU UM NOME
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca ORDER BY nome_fornecedor ASC";
        $stmt = $pdo-> prepare($sql);
        $stmt-> bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome ORDER BY nome_fornecedor ASC";
        $stmt = $pdo-> prepare($sql);
        $stmt-> bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
    $stmt = $pdo-> prepare($sql);
}

$stmt-> execute();
$fornecedores = $stmt-> fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSCA DE FORNECEDOR</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Lista de Fornecedores</h2>
    <!-- FORMULÁRIO PARA BUSCAR USUÁRIOS -->
     <form action="buscar_fornecedor.php" method="POST">
        <label for="busca">Digite o ID ou NOME do fornecedor (opcional)</label>
        <input type="text" name="busca" id="busca">
        <button type="submit">Pequisar</button>
     </form>

        <?php if(!empty($fornecedores)):?>
            <div style="
                display: flex;
                text-align: center;
                justify-content: center;
            ">
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>ENDEREÇO</th>
                    <th>TELEFONE</th>
                    <th>EMAIL</th>
                    <th>CONTATO</th>
                    <th>AÇÕES</th>
                </tr>
                <?php foreach($fornecedores as $fornecedor):?>
                    <tr>
                        <td><?=htmlspecialchars($fornecedor['id_fornecedor'])?></td>
                        <td><?=htmlspecialchars($fornecedor['nome_fornecedor'])?></td>
                        <td><?=htmlspecialchars($fornecedor['endereco'])?></td>
                        <td><?=htmlspecialchars($fornecedor['telefone'])?></td>
                        <td><?=htmlspecialchars($fornecedor['email'])?></td>
                        <td><?=htmlspecialchars($fornecedor['contato'])?></td>
                        <td>
                            <a href="alterar_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>">Alterar</a>
                            <a href="excluir_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>"
                            onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
            </div>
        <?php else: ?>
            <p>Nenhum fornecedor encontrado.</p>
        <?php endif; ?>

    <a href="../principal.php">Voltar</a>
</body>
</html>