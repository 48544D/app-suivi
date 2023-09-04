<?php
include '../includes/header-admin.php';
?>

<title>TC</title>

<main>
    <div class="container">
        <div class="header">
            <h1>TC</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                    //get all the tc societies
                    $admin_societies = $db->query('select s.id, s.name from society s JOIN user_society us on us.id_society = s.id JOIN tc t on s.id = t.id_society GROUP BY s.id, s.name ORDER BY s.name');

                    //display all tc societies
                    if ($admin_societies->rowCount() > 0) {
                        while($admin_society = $admin_societies->fetch()) {
                            echo '<a class="admin-society-group" href="admin-society.php?id=' . $admin_society['id'] . '">' . $admin_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<h1>Vous n\'avez pas de société TC</h1>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>