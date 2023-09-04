<?php
include '../includes/header-admin.php';
?>

<title>TVA Mensuelle</title>

<main>
    <div class="container">
        <div class="header">
            <h1>TVA Mensuelle</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                    //get all the tva societies
                    $user_societies = $db->query('select s.id, s.name from society s JOIN tva_mens t on s.id = t.id_society GROUP BY s.id, s.name ORDER BY s.name');

                    //display all tva societies
                    if ($user_societies->rowCount() > 0) {
                        while($user_society = $user_societies->fetch()) {
                            echo '<a class="admin-society-group" href="admin-society.php?id=' . $user_society['id'] . '">' . $user_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<p>Aucunne société TVA mensuelle</p>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>