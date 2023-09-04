<?php
include '../includes/header-admin.php';
?>

<title>IS</title>

<main>
    <div class="container">
        <div class="header">
            <h1>IS</h1>
        </div>
        <div class="content">
            <div class="admin-society">
                <?php
                //get all foncier societies
                $admin_societies = $db->query('select s.id, s.name from society s JOIN i_s on s.id = i_s.id_society GROUP BY s.id, s.name ORDER BY s.name');

                //display all foncier societies
                if ($admin_societies->rowCount() > 0) {
                    while ($admin_society = $admin_societies->fetch()) {
                        echo '<a class="admin-society-group" href="admin-society.php?id=' . $admin_society['id'] . '">' . $admin_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                    }
                } else {
                    echo '<h1>Aucune société IS</h1>';
                }

                ?>
            </div>
        </div>
    </div>
</main>