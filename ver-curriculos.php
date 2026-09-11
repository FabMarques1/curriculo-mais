<?php

require_once('config/database.php');

$result = null;
$isDisabled = "disabled";

$vagas = "SELECT id, titulo FROM tbl_vaga";
$stmt1 = $conn->prepare($vagas);
$stmt1->execute();
$resultVaga = $stmt1->get_result();

if (!empty($_GET) && $_SERVER['REQUEST_METHOD'] === "GET") {

    $filtro_vaga = $_GET['filtro_vaga'];

    if (isset($_GET['filtro_ordem']) && strtoupper($_GET['filtro_ordem']) === 'DESC') {
        $filtro_ordem = "DESC";
    } elseif(isset($_GET['filtro_ordem']) && strtoupper($_GET['filtro_ordem']) === 'ASC'){
        $filtro_ordem = "ASC";
    }

    $informacoes = "SELECT
                    u.nome,
                    u.sobrenome AS sobrenome,
                    u.email,
                    v.id,
                    v.titulo,
                    c.resumo_profissional,
                    c.curriculo
                FROM tbl_curriculo c
                INNER JOIN tbl_usuario u
                    ON c.id_usuario = u.id
                INNER JOIN tbl_vaga v
                    ON c.id_vaga = v.id
                WHERE v.id = ?
                ORDER BY nome " . $filtro_ordem;

    $stmt3 = $conn->prepare($informacoes);
    $stmt3->bind_param('i', $filtro_vaga);

}

if(empty($_GET)){
    $informacoes = "SELECT
                        u.nome,
                        u.sobrenome,
                        u.email,
                        v.id,
                        v.titulo,
                        c.resumo_profissional,
                        c.curriculo
                    FROM tbl_curriculo c
                    INNER JOIN tbl_usuario u
                        ON c.id_usuario = u.id
                    INNER JOIN tbl_vaga v
                        ON c.id_vaga = v.id";

    $stmt3 = $conn->prepare($informacoes);
}

$stmt3->execute();
$resultInfo = $stmt3->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURRÍCULO+ | Currículos submetidos</title>
</head>
<body>
    <h1>Currículos</h1>
    <form action="ver-curriculos.php" method="GET">
        <label for="filtro_ordem">Ordem por nome:</label>
        <select name="filtro_ordem" id="filtro_ordem">
            <option value="DESC">Decrescente</option>
            <option value="ASC">Crescente</option>
        </select>

        <label for="filtro_vaga">Filtrar por vaga:</label>
        <select name="filtro_vaga" id="filtro_vaga">
            <?php if ($resultVaga): ?>
                <?php while($rowVaga = $resultVaga->fetch_assoc()): ?>
                    <option value="<?php echo $rowVaga['id']; ?>"><?php echo htmlspecialchars($rowVaga['titulo']); ?></option>
                <?php endwhile; ?>
                <?php $stmt1->close(); ?>
            <?php endif; ?>
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
        <?php if($resultInfo): ?>
            <?php while($rowInfo = $resultInfo->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rowInfo['nome']; ?></td>
                    <td><?php echo $rowInfo['sobrenome']; ?></td>
                    <td><?php echo $rowInfo['email']; ?></td>
                    <td><?php echo $rowInfo['titulo']; ?></td>
                    <td><?php echo $rowInfo['resumo_profissional']; ?></td>
                    <td><a href="<?php echo $rowInfo['curriculo']; ?>">Acessar currículo</a></td>
                </tr>
            <?php endwhile; ?>
            <?php $stmt3->close(); ?>
        <?php else: ?>
            <td>-------</td>
            <td>-------</td>
            <td>-------</td>
            <td>-------</td>
            <td>-------</td>
            <td>-------</td>
            <?php $stmt3->close(); ?>
        <?php endif; ?>
    </table>
</body>
</html>
