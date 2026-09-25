<?php
    session_start();

    require_once('config/database.php');
    header('Content-Type: text/html; charset=utf-8');

if($_SESSION['tipo_usuario'] != 2) {
    header('Location: index.php');
}

$vagas = "SELECT id, titulo FROM tbl_vaga";
$stmt1 = $conn->prepare($vagas);
$stmt1->execute();
$resultVaga = $stmt1->get_result();
$stmt1->close();

$filtroVaga  = $_GET['filtro_vaga'] ?? '';
$filtroOrdem = (isset($_GET['filtro_ordem']) && strtoupper($_GET['filtro_ordem']) === 'ASC') ? 'ASC' : 'DESC';

$porPagina = 10;

$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

if ($paginaAtual < 1) {
    $paginaAtual = 1;
}

$offset = ($paginaAtual - 1) * $porPagina;

$sqlTotal = "SELECT COUNT(*) AS total
            FROM tbl_curriculo c
            INNER JOIN tbl_usuario u ON c.id_usuario = u.id
            INNER JOIN tbl_vaga v ON c.id_vaga = v.id";

if (!empty($filtroVaga)) {
    $sqlTotal .= " WHERE v.id = ?";
}

$stmtTotal = $conn->prepare($sqlTotal);

if (!empty($filtroVaga)) {
    $stmtTotal->bind_param('i', $filtroVaga);
}

$stmtTotal->execute();

$resultTotal = $stmtTotal->get_result();

$totalRegistros = $resultTotal->fetch_assoc()['total'];

$stmtTotal->close();

$totalPaginas = ceil($totalRegistros / $porPagina);


$informacoes = "SELECT
                    u.nome,
                    u.sobrenome,
                    u.email,
                    v.id,
                    v.titulo,
                    c.curriculo
                FROM tbl_curriculo c
                INNER JOIN tbl_usuario u ON c.id_usuario = u.id
                INNER JOIN tbl_vaga v ON c.id_vaga = v.id";

if (!empty($filtroVaga)) {
    $informacoes .= " WHERE v.id = ?";
}

$informacoes .= " ORDER BY u.nome " . $filtroOrdem;
$informacoes .= " LIMIT ? OFFSET ?";

$stmt3 = $conn->prepare($informacoes);

if (!empty($filtroVaga)) {
    $stmt3->bind_param('iii', $filtroVaga, $porPagina, $offset);
} else {
    $stmt3->bind_param('ii', $porPagina, $offset);
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
    <link rel="stylesheet" href="css/verCurriculo.css?v=1">
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
                <th>EMAIL</th>
                <th>VAGA APLICADA</th>
                <th>CURRÍCULO</th>
        </tr>
    <?php if ($resultInfo && $resultInfo->num_rows > 0): ?>
        <?php while ($rowInfo = $resultInfo->fetch_assoc()): ?>
            <tr>
                <td data-label="Nome"><?php echo htmlspecialchars($rowInfo['nome']) . " " . htmlspecialchars($rowInfo['sobrenome']); ?></td>
                <td data-label="Email"><?php echo htmlspecialchars($rowInfo['email']); ?></td>
                <td data-label="Vaga"><?php echo htmlspecialchars($rowInfo['titulo']); ?></td>
                <td data-label="Currículo"><a href="<?php echo htmlspecialchars($rowInfo['curriculo']); ?>" target="_blank">Acessar currículo</a></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" align="center">Nenhum currículo encontrado.</td>
        </tr>
    <?php endif; ?>
</table>

<?php if ($totalPaginas > 1): ?>
    <?php
    $paginasPorBloco = 5;

    $blocoAtual = ceil($paginaAtual / $paginasPorBloco);

    $primeiraPagina = (($blocoAtual - 1) * $paginasPorBloco) + 1;

    $ultimaPagina = min(
        $primeiraPagina + $paginasPorBloco - 1,
        $totalPaginas
    );
    ?>
    <center>
        <div class="paginacao">
            <?php if ($primeiraPagina > 1): ?>
                <a href="?pagina=<?php echo $primeiraPagina - $paginasPorBloco; ?>&filtro_vaga=<?php echo urlencode($filtroVaga); ?>&filtro_ordem=<?php echo $filtroOrdem; ?>"
                class="seta">
                    &lt;
                </a>
            <?php endif; ?>

            <?php for ($i = $primeiraPagina; $i <= $ultimaPagina; $i++): ?>
                <?php if ($i == $paginaAtual): ?>
                    <span class="ativa">
                        <?php echo $i; ?>
                    </span>
                <?php else: ?>
                    <a href="?pagina=<?php echo $i; ?>&filtro_vaga=<?php echo urlencode($filtroVaga); ?>&filtro_ordem=<?php echo $filtroOrdem; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($ultimaPagina < $totalPaginas): ?>
                <a href="?pagina=<?php echo $ultimaPagina + 1; ?>&filtro_vaga=<?php echo urlencode($filtroVaga); ?>&filtro_ordem=<?php echo $filtroOrdem; ?>"
                class="seta">
                    &gt;
                </a>
            <?php endif; ?>
        </div>
    </center>
<?php endif; ?>

</body>
</html>

<?php
    $stmt3->close();
    $conn->close();
?>
