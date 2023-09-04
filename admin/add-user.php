<?php
include '../includes/header-admin.php';
//if id is in url, get the user with the id in url
if (isset($_GET['id'])) {
    $user = $db->query('SELECT * FROM users WHERE id = ' . $_GET['id'])->fetch();
    $societies = $db->query('SELECT id_society FROM user_society WHERE id_user = ' . $_GET['id']);
    $societies_array = array();
    while ($society = $societies->fetch()) {
        array_push($societies_array, $society['id_society']);
    }
}

//check for errors
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'username') {
        echo '<script>alert("username already exists")</script>';
    }
}


//if delete is clicked, delete the user with the id in url
if (isset($_POST['delete'])) {
    $db->query('DELETE FROM users WHERE id = ' . $_POST['id']);
    $db->query('DELETE FROM user_society WHERE id_user = ' . $_POST['id']);
    header('Location: admin-users.php');
}


//if update is clicked, update the user with the id in url
if (isset($_POST['update'])) {
    $user_id = $_POST['id'];
    $old_username = $db->query('SELECT username FROM users WHERE id = ' . $user_id)->fetch()['username'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $societies = $_POST['societies'];
    //check if username already exists
    if ($username != $old_username) {
        $user = $db->query('SELECT count(*) FROM users WHERE username = "' . $username . '"')->fetchColumn();
    } else {
        $user = 0;
    }

    if ($user == 0) {
        $db->query('UPDATE users SET username = "' . $username . '" , password = "' . $password . '" WHERE id = ' . $user_id);

        //reset user societies
        $db->query('DELETE FROM user_society WHERE id_user = ' . $user_id);

        //insert new user societies
        foreach ($societies as $society) {
            $db->query('INSERT INTO user_society (id_user, id_society) VALUES ("' . $user_id . '", "' . $society . '")');
        }

        header('Location: admin-users.php');
    } else {
        header('Location: add-user.php?error=username&id=' . $user_id);
    }
}

//register form
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $societies = $_POST['societies'];
    //check if username already exists
    $user = $db->query('SELECT count(*) FROM users WHERE username = "' . $username . '"')->fetchColumn();

    if ($user == 0) {
        //insert user
        $db->query('INSERT INTO users (username, password, isAdmin) VALUES ("' . $username . '", "' . $password . '", "' . 0 . '")');

        //get user id
        $user_id = $db->query('SELECT id FROM users WHERE username = "' . $username . '"')->fetch();

        //insert user societies
        foreach ($societies as $society) {
            $db->query('INSERT INTO user_society (id_user, id_society) VALUES ("' . $user_id[0] . '", "' . $society . '")');
        }
        header('Location: admin-users.php');
    } else {
        //pass username already exists in url
        header('Location: add-user.php?error=username');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau utilisateur</title>
</head>

<script>
    function validateForm() {
        //check if username is empty
        if (document.getElementById("username").value == "") {
            alert("Veuillez entrer un nom d'utilisateur");
            return false;
        }

        //check if password is empty
        if (document.getElementById("password").value == "") {
            alert("Veuillez entrer un mot de passe");
            return false;
        }

        //check if at least one society is selected
        var checkboxes = document.getElementsByName('societies[]');
        var checked = false;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checked = true;
                break;
            }
        }
        if (!checked) {
            alert('Veuillez sélectionner au moins une société');
            return false;
        }
        return true;
    }
</script>

<body>
    <main>
        <div class="container">
            <div class="header">
                <h1>Ajouter un utilisateur</h1>
            </div>
            <div class="content">
                <form class="add-user" action="add-user.php" method="post" onsubmit="if(this.submitted) return validateForm();">
                    <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) {
                                                                echo $_GET['id'];
                                                            } ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php if (isset($_GET['id']))
                                                                                                            echo $user['username'];
                                                                                                        ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="<?php if (isset($_GET['id']))
                                            echo "text";
                                        else
                                            echo "password";
                                        ?>" name="password" id="password" class="form-control" value="<?php if (isset($_GET['id']))
                                                                                                            echo $user['password'];
                                                                                                        ?>">
                    </div>
                    <div class="societies">
                        <h2>Sociétés :</h2>
                        <?php
                        if (isset($_GET['id'])) {
                            $societies = $db->query(
                                'SELECT * FROM society s LEFT JOIN user_society u ON s.id = u.id_society where u.id_society is NULL or s.id in (SELECT id_society FROM user_society WHERE id_user = ' . $_GET['id'] . ') ORDER BY s.name'
                            );
                        } else {
                            $societies = $db->query('SELECT * FROM society s LEFT JOIN user_society u ON s.id = u.id_society where u.id_society is NULL');
                        }

                        //if societies doesn't exist, display error message
                        echo '<div class="societies-text">';
                        if ($societies->rowCount() == 0) {
                            echo '<h4>Aucune société n\'a été trouvée</h4>';
                        } else {
                            while ($society = $societies->fetch()) {
                                echo '<div class="society"> <input type="checkbox" name="societies[]" id="' . $society[0] . '" value="' . $society[0] . '" value="' . $society[0] . '"';
                                if (isset($_GET['id'])) {
                                    if (in_array($society[0], $societies_array)) {
                                        echo 'checked';
                                    }
                                }
                                echo '> <label for="' . $society[0] . '">' . $society['name'] . '</label></div>';
                            }
                        }
                        echo '</div>'
                        ?>
                    </div>
                    <div class="register-div">
                        <?php
                        if (isset($_GET['id'])) {
                            echo '<button type="submit" name="update" class="submit-btn" onclick="this.form.submitted=true">Enregistrer</button>';
                            echo '<button type="submit" name="delete" class="delete-btn" onclick="this.form.submitted=false">Supprimer</button>';
                        } else {
                            echo '<button type="submit" name="register" class="submit-btn" onclick="this.form.submitted=true">Enregistrer</button>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>