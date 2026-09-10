<?php

require_once('config/database.php');

$result = null;
$isDisabled = "disabled";

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if (isset($_GET['filtro_vaga'])) {
        $isDisabled = "";

        $sql = "SELECT id, nome FROM tbl_instituicao";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

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
        <label for="filtro_ordem">Ordem:</label>
        <select name="filtro_ordem" id="filtro_ordem">
            <option value="DESC">Decrescente</option>
            <option value="ASC">Crescente</option>
        </select>

        <label for="filtro_instituicao">Instituição</label>
        <select name="filtro_instituicao" id="filtro_instituicao" <?php echo $isDisabled; ?>>
            <?php if ($result): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <label for="filtro_vaga">Filtrar por vaga <i>(se instituição for filtrada)</i>:</label>
        <select name="filtro_vaga" id="filtro_vaga" <?php echo $isDisabled; ?>>
            <option value="vaga1">Vaga 1</option>
            <option value="vaga2">Vaga 2</option>
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
        <tr>
            <td>Fabricio</td>
            <td>Henrique</td>
            <td>fabricioteste@gmail.com</td>
            <th>Marketing</th>
            <th>Entusiasta por banco de dados, apaixonado por tecnologia.</th>
            <th><a href="curriculos/">Acessar currículo</a></th>
        </tr>
    </table>
</body>
</html>
