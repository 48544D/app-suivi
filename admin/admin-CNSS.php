<?php
include '../includes/header-admin.php';
?>

<title>CNSS</title>

<main>
    <div class="container">
        <div class="header">
            <h1>CNSS</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                    //get all the cnss societies
                    $admin_societies = $db->query('select s.id, s.name from society s JOIN user_society us on us.id_society = s.id JOIN cnss t on s.id = t.id_society GROUP BY s.id, s.name ORDER BY s.name');

                    //display all cnss societies
                    if ($admin_societies->rowCount() > 0) {
                        while($admin_society = $admin_societies->fetch()) {
                            echo '<a class="admin-society-group" href="admin-society.php?id=' . $admin_society['id'] . '">' . $admin_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<h1>Vous n\'avez pas de société CNSS</h1>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>