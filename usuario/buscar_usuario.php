<?php
session_start();
require_once '../conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO; SE É ADMIN (1) OU SECRETARIO (2)
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    header("Location: principal.php");
    echo "<script>alert('Acesso negado!');</script>";
    exit();
}

//INICIALIZA A VARIÁVEL, PARA EVITAR ERROS
$usuarios = [];

//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO PELO ID OU PELO NOME
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    //VERIFICA SE A BUSCA É UM NÚMERO (id) OU UM NOME
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo-> prepare($sql);
        $stmt-> bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo-> prepare($sql);
        $stmt-> bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo-> prepare($sql);
}

$stmt-> execute();
$usuarios = $stmt-> fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSCA DE USUÁRIO</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="container">
        <header class="container__titulo">
           <h2>Lista de Usuários</h2>
        </header>
        <!-- FORMULÁRIO PARA BUSCAR USUÁRIOS -->
        <div class="container__pesquisa">
        <form action="buscar_usuario.php" method="POST" class="container__pesquisa__forms">
            <label for="busca">Digite o ID ou NOME do usuário (opcional)</label>
            <input type="text" name="busca" id="busca">
            <button type="submit" class="btn-pesquisa">Pequisar</button>
        </form>

        <div class="container-tabela">
            <?php if(!empty($usuarios)):?>
                <table class="container-tabela__tabela">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>EMAIL</th>
                        <th>PERFIL</th>
                        <th>AÇÕES</th>
                    </tr>
                    <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?=htmlspecialchars($usuario['id_usuario'])?></td>
                            <td><?=htmlspecialchars($usuario['nome'])?></td>
                            <td><?=htmlspecialchars($usuario['email'])?></td>
                            <td><?=htmlspecialchars($usuario['id_perfil'])?></td>
                            <td>
                                <div class="tabela__btn">
                                    <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>"  class="btn-acao btn-edit">Alterar</a>
                                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>"
                                    class="btn-acao btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
                </div>
            <?php else: ?>
                <div class="container-tabela__sem-resultado">
                    <div class="container-tabela__sem-resultado__icon">🤷‍♀️</div>
                    <p>Nenhum usuário encontrado.</p>
                </div>
            <?php endif; ?>
        </div>

        <a href="../principal.php" class="btn-voltar">Voltar</a>
        <footer> Desenvolvido por Natalí Alberton Grolli - SENAI</footer>
    </main>
</body>
</html>