<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CURRICULO+ | Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
</head>
<body>
    <h3>Login</h3>

    <form action="enviar-login.php" method="POST">
        <label for="email">Digite seu e-mail</label>
        <input name="email" id="email" type="email">

        <br>

        <label for="senha">Digite sua senha:</label>
        <input name="senha" id="senha" type="password">

        <button>Login</button>
    </form>
</body>
</html>