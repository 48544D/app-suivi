<?php
include '../includes/header-user.php';
?>

<title>TVA Mensuelle</title>

<main>
    <div class="container">
        <div class="header">
            <h1>TVA Mensuelle</h1>
        </div>
        <div class="content">
            <div class="user-society">
                <?php
                    //get all user's the tva societies
                    $user_societies = $db->query('select s.id, s.name from society s JOIN user_society us on us.id_society = s.id JOIN tva_mens t on s.id = t.id_society WHERE id_user = ' . $_SESSION['id'] . ' GROUP BY s.id, s.name ORDER BY s.name');

                    //display all user's tva societies
                    if ($user_societies->rowCount() > 0) {
                        while($user_society = $user_societies->fetch()) {
                            echo '<a class="user-society-group" href="user-society.php?id=' . $user_society['id'] . '">' . $user_society['name'] . ' <i class="fas fa-arrow-right"></i></a>';
                        }
                    } else {
                        echo '<h1>Vous n\'avez pas de société TVA</h1>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
</main>