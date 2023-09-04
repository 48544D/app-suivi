<?php
include '../includes/header-admin.php';
?>

<title>IR Salarier</title>

<main>
    <div class="container">
        <div class="header">
            <h1>IR Salarier</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                    //get all salarier societies
                    $admin_societies = $db->query('select s.id, s.name from society s JOIN salarier sa on s.id = sa.id_society GROUP BY s.id, s.name ORDER BY s.name');

                    //display all salarier societies
                    if ($admin_societies->rowCount() > 0) {
                        while($admin_society = $admin_societies->fetch()) {
                            echo '<a class="admin-society-group" href="admin-society.php?id=' . $admin_society['id'] . '">' . $admin_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<h1>Aucune société IR Salarier</h1>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>