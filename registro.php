<?php

require_once("config/database.php");

$query = "SELECT id, nome FROM tbl_cidade";
$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURRÍCULO+ | Registro</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/registro.css">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
</head>
<body>
    <form action="enviar-registro.php" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Primeiro nome *</label>
                <input id="nome" name="nome" type="text" placeholder="Seu nome..." minlength="1" maxlength="40" required>
            </div>

            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input id="sobrenome" name="sobrenome" type="text" placeholder="Seu sobrenome..." minlength="1" maxlength="75">
            </div>
        </div>

        <div class="form-group">
            <label for="email">E-mail *</label>
            <input id="email" name="email" type="email" placeholder="Seu e-mail..." required>
        </div>

        <div class="form-group">
            <label for="senha">Senha *</label>
            <input id="senha" name="senha" type="password" placeholder="Sua senha..." required>
        </div>

        <div class="form-group">
            <label for="cidade">Cidade</label>
            <select name="cidade" id="cidade">
                <?php if(!$result): ?>
                    <option value="?" selected>Cidades não encontradas.</option>
                <?php else: ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
        <button>Cadastrar</button>
    </form>
</body>
</html>