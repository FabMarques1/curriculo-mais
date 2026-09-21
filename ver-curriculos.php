<?php
    session_start();

    require_once('config/database.php');
    header('Content-Type: text/html; charset=utf-8');

if($_SESSION['tipo_usuario'] != "recrutador") {
    header('Location: index.php');
}

    $vagas = "SELECT id, titulo FROM tbl_vaga";
    $stmt1 = $conn->prepare($vagas);
    $stmt1->execute();
    $resultVaga = $stmt1->get_result();
    $stmt1->close();

    $filtroVaga  = $_GET['filtro_vaga'] ?? '';
    $filtroOrdem = (isset($_GET['filtro_ordem']) && strtoupper($_GET['filtro_ordem']) === 'ASC') ? 'ASC' : 'DESC';

    $informacoes = "SELECT
                        u.nome,
                        u.sobrenome,
                        u.email,
                        v.id,
                        v.titulo,
                        c.resumo_profissional,
                        c.curriculo
                    FROM tbl_curriculo c
                    INNER JOIN tbl_usuario u ON c.id_usuario = u.id
                    INNER JOIN tbl_vaga v ON c.id_vaga = v.id";

    if (!empty($filtroVaga)) {
        $informacoes .= " WHERE v.id = ?";
    }

    $informacoes .= " ORDER BY u.nome " . $filtroOrdem;

    $stmt3 = $conn->prepare($informacoes);

    if (!empty($filtroVaga)) {
        $stmt3->bind_param('i', $filtroVaga);
    }

    $stmt3->execute();
    $resultInfo = $stmt3->get_result();

    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CURRÍCULO+ | Currículos submetidos</title>
        <link rel="stylesheet" href="css/verCurriculo.css">
    </head>
    <body>
        <h1>Currículos</h1>
        <form action="ver-curriculos.php" method="GET">

            <label for="filtro_ordem">Ordem por nome:</label>
            <select name="filtro_ordem" id="filtro_ordem">
                <option value="DESC" <?php echo $filtroOrdem === 'DESC' ? 'selected' : ''; ?>>Decrescente</option>
                <option value="ASC" <?php echo $filtroOrdem === 'ASC' ? 'selected' : ''; ?>>Crescente</option>
            </select>

            <label for="filtro_vaga">Filtrar por vaga:</label>
            <select name="filtro_vaga" id="filtro_vaga">
                <option value="">Todas as vagas</option>
                <?php while ($rowVaga = $resultVaga->fetch_assoc()): ?>
                    <option value="<?php echo $rowVaga['id']; ?>" <?php echo ($filtroVaga == $rowVaga['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rowVaga['titulo']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button>Buscar</button>
        </form>

    <table align="center" border="1" width="1200px">
        <tr>
            <th>NOME</th>
            <th>SOBRENOME</th>
            <th>EMAIL</th>
            <th>VAGA APLICADA</th>
            <th>RESUMO PROFISSIONAL</th>
            <th>CURRÍCULO</th>
        </tr>
        <?php if ($resultInfo && $resultInfo->num_rows > 0): ?>
            <?php while ($rowInfo = $resultInfo->fetch_assoc()): ?>
                <tr>
                    <td data-label="Nome"><?php echo htmlspecialchars($rowInfo['nome']); ?></td>
                    <td data-label="Sobrenome"><?php echo htmlspecialchars($rowInfo['sobrenome']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($rowInfo['email']); ?></td>
                    <td data-label="Vaga"><?php echo htmlspecialchars($rowInfo['titulo']); ?></td>
                    <td data-label="Resumo"><?php echo htmlspecialchars($rowInfo['resumo_profissional']); ?></td>
                    <td data-label="Currículo"><a href="<?php echo htmlspecialchars($rowInfo['curriculo']); ?>" target="_blank">Acessar currículo</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">Nenhum currículo encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

    <?php
    $stmt3->close();
    $conn->close();
    ?>
