<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>CURRICULO+ | Login</title>

    <link rel="stylesheet" href="css/loginOption.css">

    <link rel="shortcut icon"
        href="assets/img/favicon.png"
        type="image/x-icon">
</head>

<body>

    <main class="login-container">
          <div class="logo">
            CURRICULO<span>+</span>
        </div>

        <h1 class="title">quem é você?</h1>
        <form action="login.php" method="POST">          
            <label for="usuario">
               <h1>Usuário</h1>
            </label>

            <button type="submit">
                Login
            </button>

        </form>
        <form action="login.php" method="POST">
            <label for="recrutador">
                <h1>Recrutador</h1>
            </label>

       
            <button type="submit">
                Login
            </button>

        </form>

         <button type="button" id="btn-entrar-mobile" class="mobile-submit">
            Entrar
        </button>


    </main>

</body>

</html>
