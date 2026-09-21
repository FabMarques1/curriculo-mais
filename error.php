<?php

if(!isset($_GET['error'])){
    header("Location: index.php");
    exit;
}

$tipos = ['usuario', 'recrutador'];

$tipo = (isset($_GET['type']) && in_array($_GET['type'], $tipos, true))
    ? $_GET['type']
    : null;

$rotulosTipo = [
    'usuario'    => 'usuário',
    'recrutador' => 'recrutador',
];

$linkLogin = $tipo ? "login.php?type=" . $tipo : "loginOption.php";

$erros = [

    101 => [
        'icon'   => 'warning',
        'titulo' => 'Usuário não encontrado',
        'texto'  => $tipo
            ? "Não encontramos nenhuma conta de {$rotulosTipo[$tipo]} com esse e-mail. Confira o endereço digitado ou crie sua conta."
            : "Não encontramos nenhuma conta com esse e-mail. Confira o endereço digitado ou crie sua conta.",
        'primaria'   => ['rotulo' => 'Tentar novamente', 'href' => $linkLogin],
        'secundaria' => ['rotulo' => 'Criar conta',      'href' => 'registro.php'],
    ],

    102 => [
        'icon'   => 'warning',
        'titulo' => 'E-mail já cadastrado',
        'texto'  => 'Já existe uma conta com esse e-mail. Faça login ou use outro endereço.',
        'primaria'   => ['rotulo' => 'Fazer login',    'href' => 'loginOption.php'],
        'secundaria' => ['rotulo' => 'Usar outro e-mail', 'href' => 'registro.php'],
    ],

    103 => [
        'icon'   => 'warning',
        'titulo' => 'Arquivo inválido',
        'texto'  => 'Envie o currículo em um arquivo PDF, DOC ou DOCX válido.',
        'primaria'   => ['rotulo' => 'Escolher outro arquivo', 'href' => 'form.php'],
        'secundaria' => ['rotulo' => 'Voltar ao início',       'href' => 'index.php'],
    ],

    104 => [
        'icon'   => 'warning',
        'titulo' => 'Arquivo muito grande',
        'texto'  => 'O currículo pode ter no máximo 2 MB. Reduza o tamanho do PDF e envie de novo.',
        'primaria'   => ['rotulo' => 'Escolher outro arquivo', 'href' => 'form.php'],
        'secundaria' => ['rotulo' => 'Voltar ao início',       'href' => 'index.php'],
    ],

    201 => [
        'icon'   => 'error',
        'titulo' => 'Usuário ou senha incorretos',
        'texto'  => 'Verifique seus dados e tente novamente.',
        'primaria'   => ['rotulo' => 'Tentar novamente', 'href' => $linkLogin],
        'secundaria' => ['rotulo' => 'Voltar ao início', 'href' => 'index.php'],
    ],

    202 => [
        'icon'   => 'error',
        'titulo' => 'Não foi possível cadastrar',
        'texto'  => 'Ocorreu um problema ao criar sua conta. Tente novamente em instantes.',
        'primaria'   => ['rotulo' => 'Tentar novamente', 'href' => 'registro.php'],
        'secundaria' => ['rotulo' => 'Voltar ao início', 'href' => 'index.php'],
    ],

    500 => [
        'icon'   => 'error',
        'titulo' => 'Algo deu errado',
        'texto'  => 'Tivemos um problema inesperado. Tente novamente em alguns instantes.',
        'primaria'   => ['rotulo' => 'Voltar ao início', 'href' => 'index.php'],
        'secundaria' => null,
    ],

];

$chave = $_GET['error'] ?? '';

if (!is_string($chave) || !array_key_exists($chave, $erros)) {
    $chave = 500;
}

$erro = $erros[$chave];

$simbolos = [
    'error'   => '✕',
    'warning' => '!',
];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta name="robots" content="noindex">

    <title>CURRICULO+ | <?php echo htmlspecialchars($erro['titulo'], ENT_QUOTES, 'UTF-8'); ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/error.css">

    <link rel="shortcut icon"
        href="assets/img/favicon.png"
        type="image/x-icon">
</head>

<body>

    <main class="erro-container">
        <div class="logo">
            CURRICULO<span>+</span>
        </div>

        <section class="erro-card" role="alert">
            <div class="erro-icone erro-icone--<?php echo $erro['icon']; ?>" aria-hidden="true">
                <?php echo $simbolos[$erro['icon']]; ?>
            </div>

            <h3><?php echo htmlspecialchars($erro['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>

            <p><?php echo htmlspecialchars($erro['texto'], ENT_QUOTES, 'UTF-8'); ?></p>

            <div class="erro-acoes">
                <a class="btn btn-primario"
                    href="<?php echo htmlspecialchars($erro['primaria']['href'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($erro['primaria']['rotulo'], ENT_QUOTES, 'UTF-8'); ?>
                </a>

                <?php if ($erro['secundaria']) : ?>
                    <a class="btn btn-secundario"
                        href="<?php echo htmlspecialchars($erro['secundaria']['href'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($erro['secundaria']['rotulo'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>

</html>