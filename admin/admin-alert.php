<?php
include '../includes/header-admin.php';

$date = new DateTime();
$day = $date->format('j');
$month = $date->format('m');
$year = $date->format('Y');

$query_tri = "";
$query_mens = "";
$query_tp = "";

if ($month > 1 && $month < 4) {
    if ($month == 3 && $day > 20){
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND paied = 0 AND trimester = 1 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND paied = 0 AND trimester = 1 AND year = $year";
    } else {
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND notified = 0 AND trimester = 1 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND notified = 0 AND trimester = 1 AND year = $year";
    }
} else if ($month > 4 && $month < 7) {
    if ($month == 6 && $day > 20){
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND paied = 0 AND trimester = 2 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND paied = 0 AND trimester = 2 AND year = $year";
    } else {
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND notified = 0 AND trimester = 2 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND notified = 0 AND trimester = 2 AND year = $year";
    }
} else if ($month > 7 && $month < 10) {
    if ($month == 9 && $day > 20){
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND paied = 0 AND trimester = 3 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND paied = 0 AND trimester = 3 AND year = $year";
    } else {
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND notified = 0 AND trimester = 3 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND notified = 0 AND trimester = 3 AND year = $year";
    }
} else if ($month > 10) {
    if ($month == 12 && $day > 20){
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND paied = 0 AND trimester = 4 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND paied = 0 AND trimester = 4 AND year = $year";
    } else {
        $query_tri = "SELECT s.id, s.name FROM society s, tva_tri t WHERE s.id = t.id_society AND notified = 0 AND trimester = 4 AND year = $year UNION SELECT s.id, s.name FROM society s, i_s i WHERE s.id = i.id_society AND notified = 0 AND trimester = 4 AND year = $year";
    }
}


if ($day < 25 && $day > 5) {
    $query_mens = "SELECT s.id, s.name FROM society s, tva_mens t WHERE t.id_society = s.id and month = $month and year = $year and notified = false
    UNION SELECT s.id, s.name from society s, salarier sa WHERE s.id = sa.id_society and month = $month and year = $year and notified = false
    UNION SELECT s.id, s.name FROM society s, foncier f WHERE f.id_society = s.id and month = $month and year = $year and notified = false
    UNION SELECT s.id, s.name FROM society s, cs WHERE cs.id_society = s.id and month = $month and year = $year and notified = false
    UNION SELECT s.id, s.name FROM society s, cnss c WHERE c.id_society = c.id and month = $month and year = $year and notified = false";
} else if ($day > 25 && $day <= 31) {
    $query_mens = "SELECT s.id, s.name FROM society s, tva_mens t WHERE t.id_society = s.id and month = $month and year = $year and paied = false
    UNION SELECT s.id, s.name from society s, salarier sa WHERE s.id = sa.id_society and month = $month and year = $year and paied = false
    UNION SELECT s.id, s.name FROM society s, foncier f WHERE f.id_society = s.id and month = $month and year = $year and paied = false
    UNION SELECT s.id, s.name FROM society s, cs WHERE cs.id_society = s.id and month = $month and year = $year and paied = false
    UNION SELECT s.id, s.name FROM society s, cnss c WHERE c.id_society = c.id and month = $month and year = $year and paied = false";
}

if ($month == 12) {
    $query_tp = $db->query("SELECT s.id, s.name FROM society s, tp t WHERE t.id_society = s.id and year = $year and paied = false");
}

// $query_tc = "SELECT s.id, s.name FROM society s, tc t WHERE t.id_society = s.id and year = $year and notified = false and month > $month + 5";

$result = join(" UNION ", array($query_tri, $query_mens, $query_tp));
$result = rtrim($result, " UNION ");
$query = $db->query($result);

if ($query->rowcount() != 0) {
    echo '<div class="admin-society">';
    while ($society = $query->fetch()) {
        echo '<a class="admin-society-group" href="admin-society.php?id=' . $society['id'] . '"><p>' . $society['name'] . '</p><i class="fas fa-arrow-right"></i></a>';
    }
    echo '</div>';
}else {
    echo '<div class="admin-society">';
    echo '<p>Aucune société à notifier</p>';
    echo '</div>';
}

?>