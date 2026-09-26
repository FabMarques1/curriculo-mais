<?php

session_start();

require_once('config/database.php');

header('Content-Type: text/html; charset=utf-8');

// Verifica se o usuário está logado como recrutador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 2) {
    header('Location: index.php');
    exit;
}

// Recebe o nome do candidato pela URL
$nomeUsuario = filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);

// Verifica se o nome foi informado
if (!$nomeUsuario) {
    header('Location: ver-curriculos.php');
    exit;
}

// Busca os dados do candidato
$sql = "SELECT
            u.id,
            u.nome,
            u.sobrenome,
            u.email,
            u.data_nascimento,
            v.titulo,
            c.curriculo
        FROM tbl_usuario u
        INNER JOIN tbl_curriculo c ON c.id_usuario = u.id
        INNER JOIN tbl_vaga v ON c.id_vaga = v.id
        WHERE CONCAT(u.nome, ' ', u.sobrenome) = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar consulta.");
}

$stmt->bind_param('s', $nomeUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();

    header('Location: ver-curriculos.php');
    exit;
}

$candidato = $result->fetch_assoc();

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURRÍCULO+ | Perfil do candidato</title>
    <link rel="stylesheet" href="css/perfil-candidato.css">
    <link
        rel="shortcut icon"
        href="assets/img/favicon.png"
        type="image/x-icon"
    >

</head>

<body>
    <main>
        <a href="ver-curriculos.php">
            ← Voltar para candidatos
        </a>
        <header>
            <h1>Perfil do candidato</h1>

            <p>
                Informações do candidato e currículo enviado.
            </p>

        </header>

        <section>

            <h2>

                <?php

                echo htmlspecialchars(
                    $candidato['nome'] . ' ' . $candidato['sobrenome']
                );

                ?>

            </h2>

            <div>

                <strong>E-mail</strong>

                <span>
                    <?php echo htmlspecialchars($candidato['email']); ?>
                </span>

            </div>

            <div>
                <strong>Data de nascimento</strong>

                <span>
                    <?php
                    echo date(
                        'd/m/Y',
                        strtotime($candidato['data_nascimento'])
                    );
                    ?>
                </span>

            </div>

            <div>
                <strong>Vaga aplicada</strong>
                <span>
                    <?php echo htmlspecialchars($candidato['titulo']); ?>
                </span>
            </div>

        </section>

        <section>
            <h2>Currículo</h2>
            <p>
                O candidato enviou um currículo para esta vaga.
            </p>

            <?php if (!empty($candidato['curriculo'])): ?>

                <a
                    href="<?php echo htmlspecialchars($candidato['curriculo']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Visualizar currículo
                </a>

            <?php else: ?>

                <p>
                    Este candidato não possui um currículo disponível.
                </p>

            <?php endif; ?>
        </section>

    </main>

</body>
</html>