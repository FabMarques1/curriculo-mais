<?php
session_start();

if (!$_SESSION['login']) {
    header("Location: index.php");
}

require_once("config/database.php");

$query = "SELECT id, nome FROM tbl_cidade";
$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Currículo+ | Envio de currículo</title>
<link rel="stylesheet" href="css/style.css">
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
                    <p>Todos os campos com * são obrigatórios.</p>
                </div>

                <form action="enviar-curriculo.php" method="POST" enctype="multipart/form-data">

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