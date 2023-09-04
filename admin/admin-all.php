<?php
    include '../includes/header-admin.php';
    
    $societies = $db->query('SELECT * FROM society ORDER BY name');
    if ($societies->rowCount() == 0) {
        echo '<h3>Aucune société</h3>';
    } else {
        echo '<div class="admin-society">';
        while ($society = $societies->fetch()) {
            echo '<a class="admin-society-group" href="admin-society.php?id=' . $society['id'] . '"><p>' . $society['name'] . '</p><i class="fas fa-arrow-right"></i></a>';
        }
        echo '</div>';
    }
