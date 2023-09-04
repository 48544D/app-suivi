<?php
    include '../includes/header-user.php';
    
    $user_societies = $db->query('SELECT society.id, society.name FROM society INNER JOIN user_society ON society.id = user_society.id_society WHERE user_society.id_user = ' . $_SESSION['id'] . ' ORDER BY society.name');

    if ($user_societies->rowCount() == 0) {
        echo '<h3>Vous n\'avez pas de société</h3>';
    } else {
        echo '<div class="user-society">';
        while ($user_society = $user_societies->fetch()) {
            echo '<a class="user-society-group" href="user-society.php?id=' . $user_society['id'] . '"><p>' . $user_society['name'] . '</p><i class="fas fa-arrow-right"></i></a>';
        }
        echo '</div>';
    }
?>