<?php
include '../includes/header-admin.php';
?>

<title>Utilisateurs</title>

<main>
    <div class="container">
        <div class="header">
            <h1>Utilisateurs</h1>
        </div>
        <div class="content">
            <?php
            $users = $db->query('SELECT * FROM users where isAdmin = 0');
            //if users doesn't exist, display error message
            if ($users->rowCount() == 0) {
                echo '<h3>Aucun utilisateur n\'a été trouvé</h3>';
            } else {
                echo '<table class="Users-table">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Username</th>';
                echo '<th>password</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                while ($user = $users->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $user['id'] . '</td>';
                    echo '<td>' . $user['username'] . '</td>';
                    echo '<td>' . $user['password'] . '</td>';
                    echo '<td>';
                    echo '<a href="add-user.php?id=' . $user['id'] . '"><i class="fas fa-edit"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            ?>
            <div class="add-user">
                <a href="add-user.php"><i class="fas fa-plus"></i>Ajouter un utilisateur</a>
            </div>
        </div>
</main>