<?php
include './includes/connection.php';
@session_start();
//check if user is logged in
if (isset($_SESSION['user']) && isset($_SESSION['Admin'])) {
    header('Location: ./admin/admin.php');
} elseif (isset($_SESSION['user'])) {
    header('Location: ./user/user.php');
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $db->query('SELECT * FROM users WHERE username = "' . $username . '" AND password = "' . $password . '"')->fetch();
    if ($user) {
        if ($user['isAdmin'] == 1)
            $_SESSION['Admin'] = TRUE;
        $_SESSION['user'] = $username;
        $_SESSION['id'] = $user['id'];
        header('Location: index.php');
    } else {
        $error = '<div class="error"><h3>Veuillez vérifier votre nom d\'utilisateur et votre mot de passe</h3></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h3>Login</h3>
            </div>
            <div class="body">
                <?php if (isset($error)) {
                    echo $error;
                } ?>
                <form action="index.php" method="post">
                    <div class="body-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username">
                    </div>
                    <div class="body-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div class="body-group">
                        <button type="submit" name="login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <p xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/"><a property="dct:title" rel="cc:attributionURL" href="https://github.com/48544D/app-suivi">App de suivi des dépôts</a> by <a rel="cc:attributionURL dct:creator" property="cc:attributionName" href="https://github.com/48544D">Haytham aaraba</a> is licensed under <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/?ref=chooser-v1" target="_blank" rel="license noopener noreferrer" style="display:inline-block;">CC BY-NC-SA 4.0<img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/cc.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/by.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/nc.svg?ref=chooser-v1"><img style="height:22px!important;margin-left:3px;vertical-align:text-bottom;" src="https://mirrors.creativecommons.org/presskit/icons/sa.svg?ref=chooser-v1"></a></p>
</body>

</html>