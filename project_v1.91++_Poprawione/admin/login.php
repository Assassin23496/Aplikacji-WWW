<?php
declare(strict_types=1);

require_once __DIR__ . "/../cfg.php";

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = (string)($_POST['login'] ?? '');
    $p = (string)($_POST['pass'] ?? '');

    if (Auth::attempt($u, $p)) {
        header("Location: admin.php");
        exit();
    }
    $error = "Błędne dane logowania!";
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="container" style="max-width:520px;">
    <div class="card">
        <div class="card-h">
            <h1>Logowanie do panelu</h1>
        </div>
        <div class="card-b">
            <?php if ($error): ?>
                <div class="notice danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <label>Login</label>
                <input type="text" name="login" required>

                <label>Hasło</label>
                <input type="password" name="pass" required>

                <div style="margin-top:12px;">
                    <button class="btn primary" type="submit">Zaloguj</button>
                </div>

                <div class="small" style="margin-top:10px;">
                    Domyślne dane (jeśli nie zmieniałeś): admin / admin
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
