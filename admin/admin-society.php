<?php
include '../includes/header-admin.php';

$society = $db->query('SELECT * FROM society where id = ' . $_GET['id'])->fetch();
$name = $society['name'];
$user = $db->query('SELECT * FROM users u JOIN user_society us on us.id_user = u.id WHERE id_society = ' . $_GET['id'])->fetch();
if ($user) {
    $user_name = $user['username'];
} else {
    $user_name = "None";
}

//page title
echo '<title>' . $name . '</title>';

//check if this society is in tva_mens table
$isTva_mens = $db->query('SELECT * FROM tva_mens where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in tva_tri table
$isTva_tri = $db->query('SELECT * FROM tva_tri where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in salarier table
$isSalarier = $db->query('SELECT * FROM salarier where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in foncier table
$isFoncier = $db->query('SELECT * FROM foncier where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in cs table
$isCs = $db->query('SELECT * FROM cs where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in i_s table
$isIs = $db->query('SELECT * FROM i_s where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in tp table
$isTp = $db->query('SELECT * FROM tp where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in cnss table
$isCnss = $db->query('SELECT * FROM cnss where id_society = ' . $_GET['id'])->rowCount() > 0;

//check if this society is in tc table
$isTc = $db->query('SELECT * FROM tc where id_society = ' . $_GET['id'])->rowCount() > 0;
?>

<main>
    <div class="container">
        <div class="header">
            <h1><?php echo $name . ' (' . $user_name . ')' ?></h1>
        </div>
        <div class="content">
            <?php
            if ($isTva_mens || $isTva_tri || $isSalarier || $isFoncier || $isCs) {
                echo '<h2>DGI</h2>';
            }

            //chek if this society is in tva table
            if ($isTva_mens) {
                echo '<div class="DGI-society-page">';
                echo '<p class="society-page-title">TVA :</p>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<th>Mois ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                $tva = $db->query('SELECT * FROM tva_mens where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY month ASC');
                echo '<tr>';
                while ($tva_month = $tva->fetch()) {

                    //check if month is bigger than todays month
                    if ($tva_month['month'] > $date->format('m')) {
                        echo '<td class="a-paye">a paye</td>';
                    }

                    //check if month is the same as the month in the database
                    elseif ($date->format('n') == $tva_month['month']) {
                        //tva id
                        echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';

                        //upload the tva file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="notified" name="notified"';
                            if ($tva_month['notified'] == 1) {
                                echo 'checked disabled>
                                        <label for="notified">Client notifié</label>';
                            } else {
                                echo 'disabled>
                                        <label for="notified">Client non notifié</label>';
                            }
                            echo '
                                    </div>
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($tva_month['paied'] == 0) {
                                if ($tva_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" checked disabled>
                                            <label for="notified">client notifié</label>
                                        </div>
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" disabled>
                                            <label for="notified">client non notifié</label>
                                        </div>
                                        </div>
                                        </td>';
                                }
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" checked disabled>
                                            <label for="notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        } else {
                            if ($tva_month['paied'] == 0) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" checked disabled>
                                            <label for="notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" disabled>
                                            <label for="notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    </div>
                                    </td>';
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" checked disabled>
                                            <label for="notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        }
                    } else {
                        if ($tva_month['paied'] == 1)
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                </div>
                                </td>';
                        else
                            echo '
                                <td class="non-paye">
                                <div class="payement-content">
                                    non paye
                                </div>
                                </div>
                                </td>';
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</div>';
                echo '</form>';
            } elseif ($isTva_tri) {
                echo '<div class="DGI-society-page">';
                echo '<p class="society-page-title">TVA :</p>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 4; $i++) {
                    echo '<th>Trimistre ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();

                //select this year's data
                $tva = $db->query('SELECT * FROM tva_tri where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY trimester ASC');
                echo '<tr>';
                while ($tva_month = $tva->fetch()) {
                    //check the trimister
                    switch ($tva_month['trimester']) {
                        case 1:
                            if ($date->format('m') > 3) {
                                if ($tva_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 3 && $date->format('m') < 4 && $date->format('j') > 20) {
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" disabled>
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 1) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" checked disabled>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" disabled>
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" 
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo 'disabled>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 2:
                            if ($date->format('m') < 4) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 6) {
                                if ($tva_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 6 && $date->format('m') < 7 && $date->format('j') > 20) {
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" disabled>
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 4) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" checked disabled>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" disabled>
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 4) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" 
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo ' disabled>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 3:
                            if ($date->format('m') < 7) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 9) {
                                if ($tva_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 9 && $date->format('m') < 10 && $date->format('j') > 20) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" disabled>
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 7) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" checked disabled>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified"  disabled>
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 7) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked  disabled>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                         disabled>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 4:
                            if ($date->format('m') < 10) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 12) {
                                if ($tva_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') == 12 && $date->format('j') > 20) {
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" disabled>
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 10) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" checked disabled>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified" disabled>
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked disabled>
                                                <label for="notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $tva_month['id'] . '">' . $tva_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 10) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo ' disabled>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;
                    }
                }
                echo '</table>';
                echo '</div>';
                echo '</form>';
            }

            if ($isSalarier || $isFoncier || $isCs) {
                echo '<div class="DGI-society-page">';
                echo '<p class="society-page-title">IR :</p>';
            }

            if ($isSalarier) {
                echo '<h3 class="society-page-under-title">Salarié :</h3>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<th>Mois ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                $salarier = $db->query('SELECT * FROM salarier where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY month ASC');
                echo '<tr>';
                while ($salarier_month = $salarier->fetch()) {

                    //check if month is bigger than todays month
                    if ($salarier_month['month'] > $date->format('m')) {
                        echo '<td class="a-paye">a paye</td>';
                    }
                    //check if month is the same as the month in the database
                    elseif ($date->format('n') == $salarier_month['month']) {

                        //salarier file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="salarier_notified" name="salarier_notified"';
                            if ($salarier_month['notified'] == 1) {
                                echo 'checked disabled>
                                    <label for="salarier_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="salarier_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($salarier_month['paied'] == 0) {
                                if ($salarier_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked disabled>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" disabled>
                                            <label for="salarier_notified">client non notifié</label>
                                        </div>
                                        </div>
                                        </td>';
                                }
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked disabled>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $salarier_month['id'] . '">' . $salarier_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        } else {
                            if ($salarier_month['paied'] == 0) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                    ';
                                if ($salarier_month['notified'] == 1) {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked disabled>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" disabled>
                                            <label for="salarier_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    </div>
                                    </td>';
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked disabled>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $salarier_month['id'] . '">' . $salarier_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        }
                    } else {
                        if ($salarier_month['paied'] == 1)
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <a href="../download.php?id=' . $salarier_month['id'] . '">' . $salarier_month['file'] . '</a>
                                </div>
                                </td>';
                        else
                            echo '
                                <td class="non-paye">
                                <div class="payement-content">
                                    non paye
                                </div>
                                </div>
                                </td>';
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</form>';
            }

            if ($isFoncier) {
                echo '<h3 class="society-page-under-title">Foncier :</h3>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<th>Mois ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                $foncier = $db->query('SELECT * FROM foncier where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY month ASC');
                echo '<tr>';
                while ($foncier_month = $foncier->fetch()) {

                    //check if month is bigger than todays month
                    if ($foncier_month['month'] > $date->format('m')) {
                        echo '<td class="a-paye">a paye</td>';
                    }
                    //check if month is the same as the month in the database
                    elseif ($date->format('n') == $foncier_month['month']) {

                        //upload the foncier file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="foncier_notified" name="foncier_notified"';
                            if ($foncier_month['notified'] == 1) {
                                echo 'checked disabled>
                                    <label for="foncier_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="foncier_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($foncier_month['paied'] == 0) {
                                if ($foncier_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked disabled>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" disabled>
                                            <label for="foncier_notified">client non notifié</label>
                                        </div>
                                        </div>
                                        </td>';
                                }
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked disabled>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $foncier_month['id'] . '">' . $foncier_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        } else {
                            if ($foncier_month['paied'] == 0) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                    ';
                                if ($foncier_month['notified'] == 1) {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked disabled>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" disabled>
                                            <label for="foncier_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    </div>
                                    </td>';
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked disabled>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $foncier_month['id'] . '">' . $foncier_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        }
                    } else {
                        if ($foncier_month['paied'] == 1)
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <a href="../download.php?id=' . $foncier_month['id'] . '">' . $foncier_month['file'] . '</a>
                                </div>
                                </td>';
                        else
                            echo '
                                <td class="non-paye">
                                <div class="payement-content">
                                    non paye
                                </div>
                                </div>
                                </td>';
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</form>';
            }

            if ($isCs) {
                echo '<h3 class="society-page-under-title">Contirbution Sociale :</h3>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<th>Mois ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                $cs = $db->query('SELECT * FROM cs where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY month ASC');
                echo '<tr>';
                while ($cs_month = $cs->fetch()) {

                    //check if month is bigger than todays month
                    if ($cs_month['month'] > $date->format('m')) {
                        echo '<td class="a-paye">a paye</td>';
                    }
                    //check if month is the same as the month in the database
                    elseif ($date->format('n') == $cs_month['month']) {

                        //upload the cs file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="cs_notified" name="cs_notified"';
                            if ($cs_month['notified'] == 1) {
                                echo 'checked disabled>
                                    <label for="cs_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="cs_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($cs_month['paied'] == 0) {
                                if ($cs_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked disabled>
                                            <label for="cs_notified">client notifié</label>
                                        </div>
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" disabled>
                                            <label for="cs_notified">client non notifié</label>
                                        </div>
                                        </div>
                                        </td>';
                                }
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked disabled>
                                            <label for="cs_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $cs_month['id'] . '">' . $cs_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        } else {
                            if ($cs_month['paied'] == 0) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                    ';
                                if ($cs_month['notified'] == 1) {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked disabled>
                                            <label for="cs_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" disabled>
                                            <label for="cs_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    </div>
                                    </td>';
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked disabled>
                                            <label for="cs_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $cs_month['id'] . '">' . $cs_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        }
                    } else {
                        if ($cs_month['paied'] == 1)
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <a href="../download.php?id=' . $cs_month['id'] . '">' . $cs_month['file'] . '</a>
                                </div>
                                </td>';
                        else
                            echo '
                                <td class="non-paye">
                                <div class="payement-content">
                                    non paye
                                </div>
                                </div>
                                </td>';
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</form>';
                echo '</div>';
            }

            if ($isIs) {
                echo '<div class="DGI-society-page">';
                echo '<p class="society-page-title">IS :</p>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 4; $i++) {
                    echo '<th>Trimistre ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                //select data of this year
                $is = $db->query('SELECT * FROM i_s where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY trimester ASC');
                echo '<tr>';
                while ($is_month = $is->fetch()) {
                    //check the trimister
                    switch ($is_month['trimester']) {
                        case 1:
                            if ($date->format('m') > 3) {
                                if ($is_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 3 && $date->format('m') < 4 && $date->format('j') > 20) {
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 1) {
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo 'disabled>
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 2:
                            if ($date->format('m') < 4) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 6) {
                                if ($is_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 6 && $date->format('m') < 7 && $date->format('j') > 20) {
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 4) {
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 4) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 3:
                            if ($date->format('m') < 7) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 9) {
                                if ($is_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') >= 9 && $date->format('m') < 10 && $date->format('j') > 20) {
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 7) {
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 7) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo 'disabled>
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;

                        case 4:
                            if ($date->format('m') < 10) {
                                echo '<td class="a-paye">
                                        <div class="payement-month-container"> a paye </div>
                                    </td>';
                            } elseif ($date->format('m') > 12) {
                                if ($is_month['paied'] == 1)
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                        </div>
                                        </td>';
                                else
                                    echo '
                                        <td class="non-paye">
                                        <div class="payement-content">
                                            non paye
                                        </div>
                                        </div>
                                        </td>';
                            } elseif ($date->format('m') == 12 && $date->format('j') > 20) {
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 10) {
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" disabled>
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 10) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked disabled>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        </div>
                                    </td>
                                    ';
                            }
                            break;
                    }
                }
                echo '</table>';
                echo '</div>';
                echo '</form>';
            }

            if ($isTp) {
                echo '<div class="DGI-society-page">';
                echo '<p class="society-page-title">TP :</p>';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                //todays date
                $date = new DateTime();
                echo '<th>' . $date->format('Y') . '</th>';
                echo '</tr>';
                //select data of this year
                $tp = $db->query('SELECT * FROM tp where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id']);
                echo '<tr>';
                while ($tp_year = $tp->fetch()) {
                    if ($date->format('m') <= 11) {
                        echo '<input type="hidden" name="tp_id" value="' . $tp_year['id'] . '">';
                        if ($tp_year['paied'] == 0) {
                            if ($tp_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified" checked disabled>
                                            <label for="tp_notified">client notifié</label>
                                        </div>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified" disabled>
                                            <label for="tp_notified">client non notifié</label>
                                        </div>
                                    ';
                            }
                            echo '
                                </div>
                                </td>';
                        } else {
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="tp_notified" name="tp_notified" checked disabled>
                                        <label for="tp_notified">client notifié</label>
                                    </div>
                                    <a href="../download.php?id=' . $tp_year['id'] . '">' . $tp_year['file'] . '</a>
                                    </div>
                                </td>';
                        }
                    } else {
                        echo '<input type="hidden" name="tp_id" value="' . $tp_year['id'] . '">';
                        if ($tp_year['paied'] == 0) {
                            if ($tp_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified" checked disabled>
                                            <label for="tp_notified">client notifié</label>
                                        </div>
                                    </div>
                                    </td>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified" disabled>
                                            <label for="tp_notified">client non notifié</label>
                                        </div>
                                    </div>
                                    </td>
                                    ';
                            }
                        } else {
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="tp_notified" name="tp_notified" checked disabled>
                                        <label for="tp_notified">client notifié</label>
                                    </div>
                                    <a href="../download.php?id=' . $tp_year['id'] . '">' . $tp_year['file'] . '</a>
                                    </div>
                                </td>';
                        }
                    }
                }
                echo '</table>';
                echo '</div>';
            }

            if ($isCnss) {
                echo '<h2>CNSS</h2>';
                echo '<div class="DGI-society-page">';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                for ($i = 1; $i <= 12; $i++) {
                    echo '<th>Mois ' . $i . '</th>';
                }
                echo '</tr>';
                //todays date
                $date = new DateTime();
                $cnss = $db->query('SELECT * FROM cnss where year = ' . $date->format('Y') . ' and id_society = ' . $_GET['id'] . ' ORDER BY month ASC');
                echo '<tr>';
                while ($cnss_month = $cnss->fetch()) {

                    //check if month is bigger than todays month
                    if ($cnss_month['month'] > $date->format('m')) {
                        echo '<td class="a-paye">a paye</td>';
                    }

                    //check if month is the same as the month in the database
                    elseif ($date->format('n') == $cnss_month['month']) {

                        //the cnss file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="cnss_notified" name="cnss_notified"';
                            if ($cnss_month['notified'] == 1) {
                                echo 'checked disabled>
                                        <label for="cnss_notified">Client notifié</label>';
                            } else {
                                echo '>
                                        <label for="cnss_notified">Client non notifié</label>';
                            }
                            echo '
                                    </div>
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($cnss_month['paied'] == 0) {
                                if ($cnss_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked disabled>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" disabled>
                                            <label for="cnss_notified">client non notifié</label>
                                        </div>
                                        </div>
                                        </td>';
                                }
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked disabled>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $cnss_month['id'] . '">' . $cnss_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        } else {
                            if ($cnss_month['paied'] == 0) {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                    ';
                                if ($cnss_month['notified'] == 1) {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked disabled>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" disabled>
                                            <label for="cnss_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    </div>
                                    </td>';
                            } else {
                                echo '
                                    <td class="paye">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked disabled>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>
                                        <a href="../download.php?id=' . $cnss_month['id'] . '">' . $cnss_month['file'] . '</a>
                                        </div>
                                    </td>';
                            }
                        }
                    } else {
                        if ($cnss_month['paied'] == 1)
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <a href="../download.php?id=' . $cnss_month['id'] . '">' . $cnss_month['file'] . '</a>
                                </div>
                                </td>';
                        else
                            echo '
                                <td class="non-paye">
                                <div class="payement-content">
                                    non paye
                                </div>
                                </div>
                                </td>';
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</div>';
                echo '</form>';
            }

            if ($isTc) {
                echo '<h2>TC</h2>';
                echo '<div class="DGI-society-page">';
                echo '<form action="" method="post" enctype="multipart/form-data">';
                echo '<table class="society-page-table">';
                echo '<tr>';
                //todays date
                $date = new DateTime();
                //select data of this year
                $tc = $db->query('SELECT max(year), id, id_society, month, year, file, notified, paied FROM tc where id_society = ' . $_GET['id']);

                while ($tc_year = $tc->fetch()) {
                    $tc_month = new DateTime(strval($tc_year['year']) . '-' . strval($tc_year['month']) . '-' . $date->format('d'));
                    echo '<tr>';
                    echo '<th>' . $date->format('Y') . ' (A payé en ' . $tc_month->format('F') . ')</th>';
                    echo '</tr>';

                    $month = $tc_year['month'] + 5;
                    if ($month > 12) {
                        $month = $month - 12;
                    }

                    //check the month
                    if ($date->format('m') < $month) {
                        if ($tc_year['paied'] == 0) {
                            if ($tc_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" checked disabled>
                                            <label for="tc_notified">client notifié</label>
                                    </div>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" disabled>
                                            <label for="tc_notified">client non notifié</label>
                                        </div>
                                    ';
                            }
                            echo '
                                </div>
                                </td>';
                        } else {
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="tc_notified" name="tc_notified" checked disabled>
                                        <label for="tc_notified">client notifié</label>
                                    </div>
                                    <a href="../download.php?id=' . $tc_year['id'] . '">' . $tc_year['file'] . '</a>
                                    </div>
                                </td>';
                        }
                    } else {
                        if ($tc_year['paied'] == 0) {
                            if ($tc_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" checked disabled>
                                            <label for="tc_notified">client notifié</label>
                                    </div>
                                    </div>
                                    </td>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" disabled>
                                            <label for="tc_notified">client non notifié</label>
                                        </div>
                                    </div>
                                    </td>
                                    ';
                            }
                        } else {
                            echo '
                                <td class="paye">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="tc_notified" name="tc_notified" checked disabled>
                                        <label for="tc_notified">client notifié</label>
                                    </div>
                                    <a href="../download.php?id=' . $tc_year['id'] . '">' . $tc_year['file'] . '</a>
                                    </div>
                                </td>';
                        }
                    }
                }
                echo '</tr>';
                echo '</div>';
                echo '</table>';
                echo '</div>';
                echo '</form>';
            }
            ?>
        </div>
    </div>
</main>