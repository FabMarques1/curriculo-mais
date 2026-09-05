<?php
session_start();

require_once("config/database.php");

if(isset($_SESSION['logado'])) {
    $nome = $_SESSION['nome'];
    $sobrenome = $_SESSION['sobrenome'];
    $email = $_SESSION['email'];
    $cidade = $_SESSION['cidade'];
}

require_once("config/database.php");

$query = "SELECT nome FROM tbl_cidade WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cidade);
$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currículo+ | Envio de currículo</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body class="curriculo-page">

<main class="curriculo-main">
    <section class="curriculo-section">
        <div class="curriculo-container">

            <div class="curriculo-info">
                <p class="tag">TRABALHE CONOSCO</p>
                <h1>Envie seu currículo <span>e faça parte do time</span></h1>
                <p>Preencha o formulário ao lado com seus dados e anexe seu currículo. Nossa equipe de recrutamento entrará em contato assim que houver uma vaga compatível com seu perfil.</p>

                <div class="curriculo-benefits">
                    <div>
                        <strong>01</strong>
                        <span>Cadastro rápido e simples</span>
                    </div>
                    <div>
                        <strong>02</strong>
                        <span>Análise por recrutadores especializados</span>
                    </div>
                    <div>
                        <strong>03</strong>
                        <span>Oportunidades em toda a região</span>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-header">
                    <span>FORMULÁRIO</span>
                    <h2>Seus dados</h2>
                    <p>Confira se seus dados estão certos antes de enviar.</p>
                </div>

                <form action="enviar-curriculo.php" method="POST" enctype="multipart/form-data">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Primeiro nome</label>
                            <i><?php echo $nome; ?></i>
                        </div>

                        <div class="form-group">
                            <label for="sobrenome">Sobrenome</label>
                            <i><?php echo $sobrenome; ?></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <i><?php echo $email; ?></i>
                    </div>

                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <i><?php echo $row['nome']; ?></i>
                    </div>

                    <div class="form-group">
                        <label for="resumoProfissional">Resumo profissional</label>
                        <textarea name="resumoProfissional" id="resumoProfissional" placeholder="Conte-nos um pouco sobre você..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="curriculo">Currículo *</label>
                        <div class="file-area">
                            <div class="file-icon">&#128196;</div>
                            <div>
                                <strong>Clique ou arraste seu arquivo aqui</strong>
                                <p>PDF, DOC ou DOCX até 2MB</p>
                            </div>
                            <input name="curriculo" id="curriculo" type="file" required>
                        </div>
                    </div>

                    <button type="submit" class="submit-button">Enviar currículo</button>

                    <p class="privacy-text">Seus dados serão utilizados apenas para fins de recrutamento e seleção, conforme nossa política de privacidade.</p>

                </form>
            </div>

        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>CURRÍCULO<span>+</span></p>
        <small>&copy; 2026 Todos os direitos reservados.</small>
    </div>
</footer>

</body>
</html>

<?php

$stmt->close();
$conn->close();

?>