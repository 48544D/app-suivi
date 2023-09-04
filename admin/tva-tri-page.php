<?php
include '../includes/header-admin.php';
?>

<title>TVA Trimistrielle</title>

<main>
    <div class="container">
        <div class="header">
            <h1>TVA Trimistrielle</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                    //get all the tva societies
                    $societies = $db->query('select s.id, s.name from society s JOIN tva_tri t on s.id = t.id_society GROUP BY s.id, s.name ORDER BY s.name');

                    //display all tva societies
                    if ($societies->rowCount() > 0) {
                        while($society = $societies->fetch()) {
                            echo '<a class="admin-society-group" href="admin-society.php?id=' . $society['id'] . '">' . $society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<p>Aucunne société TVA trimistrielle</p>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>