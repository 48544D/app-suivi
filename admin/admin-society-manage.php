<?php
include '../includes/header-admin.php';
?>

<title>Societés</title>

<main>
    <div class="container">
        <div class="header">
            <h1>Sociétés</h1>
        </div>
        <div class="content">
            <?php
            $societes = $db->query('SELECT * FROM society');

            //if society doesn't exist, display error message
            if ($societes->rowCount() == 0) {
                echo '<h3>Aucune société n\'a été trouvé</h3>';
            } else {
                echo '<table class="Society-table">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nom</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                while ($societe = $societes->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $societe['id'] . '</td>';
                    echo '<td>' . $societe['name'] . '</td>';
                    echo '<td>';
                    echo '<a href="add-society.php?id=' . $societe['id'] . '"><i class="fas fa-edit"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            ?>
            <div class="add-society">
                <a href="add-society.php"><i class="fas fa-plus"></i>Ajouter une société</a>
            </div>
        </div>
</main>