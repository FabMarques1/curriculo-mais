<?php

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $filtro_vaga = $_GET['filtro_vaga'] ?? '';

    if (empty($filtro_vaga)){
        $isDisabled = "disabled";
    } else {
        $isDisabled = "";
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

        <label for="filtro_vaga">Filtrar por vaga:</label>
        <select name="filtro_vaga" id="filtro_vaga">
            <option value="vaga1">Vaga 1</option>
            <option value="vaga2">Vaga 2</option>
        </select>

        <label for="filtro_instituicao">Instituição <i>(se vaga for filtrada)</i>:</label>
        <select name="filtro_instituicao" id="filtro_instituicao" <?php echo $isDisabled; ?>>
            <option value="instituicao1">Instituição 1</option>
            <option value="instituicao2">Instituição 2</option>
            <option value="instituicao3">Instituição 3</option>
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
            <td>Nome do usuário</td>
            <td>Sobrenome do usuário</td>
            <td>Email</td>
            <th>Vaga aplicada | Instituição</th>
            <th>Resumo profissional com limite de caracteres e palavras por linha</th>
            <th><a href="curriculos/">Acessar currículo</a></th>
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
