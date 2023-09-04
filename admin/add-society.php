<?php
include '../includes/header-admin.php';
//if id is in url, get the society with the id in url
if (isset($_GET['id'])) {
    $society = $db->query('SELECT * FROM society WHERE id = ' . $_GET['id'])->fetch();
    $tva_mens = $db->query('SELECT count(*) FROM tva_mens WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $tva_tri = $db->query('SELECT count(*) FROM tva_tri WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $salarier = $db->query('SELECT count(*) FROM salarier WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $foncier = $db->query('SELECT count(*) FROM foncier WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $cs = $db->query('SELECT count(*) FROM cs WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $is = $db->query('SELECT count(*) FROM i_s WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $tp = $db->query('SELECT count(*) FROM tp WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $cnss = $db->query('SELECT count(*) FROM cnss WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $tc = $db->query('SELECT count(*) FROM tc WHERE id_society = ' . $_GET['id'])->fetchColumn();
    $tc_month = $db->query('SELECT month FROM tc WHERE id_society = ' . $_GET['id'])->fetchColumn();
}

//check for errors
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'society') {
        echo '<script>alert("society already exists")</script>';
    }
}

//if delete is clicked, delete the society with the id in url
if (isset($_POST['delete'])) {
    $db->query('DELETE FROM society WHERE id = ' . $_POST['id']);
    $db->query('DELETE FROM user_society WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM tva_mens WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM tva_tri WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM salarier WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM foncier WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM cs WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM i_s WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM tp WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM cnss WHERE id_society = ' . $_POST['id']);
    $db->query('DELETE FROM tc WHERE id_society = ' . $_POST['id']);
    
    header('Location: admin-society-manage.php');
}

$date = new DateTime();
$year = $date->format('Y');

//if update is clicked, update the society with the id in url
if (isset($_POST['update'])) {
    $society_id = $_POST['id'];
    $old_name = $db->query('SELECT name FROM society WHERE id = ' . $society_id)->fetchColumn();
    $name = $_POST['name'];
    $choices = $_POST['choices'];
    //check if society name already exists
    if ($name != $old_name) {
        $society = $db->query('SELECT count(*) FROM society WHERE name = "' . $name . '"')->fetchColumn();
    } else {
        $society = 0;
    }

    if ($society == 0) {

        //delete all tva_mens for this society if tva_mens is not selected anymore
        if (!in_array("TVA_Mens", $choices)) {
            $db->query('DELETE FROM tva_mens WHERE id_society = ' . $society_id);
        }
        //delete all tva_tri for this society if tva_tri is not selected anymore
        if (!in_array("TVA_Tri", $choices)) {
            $db->query('DELETE FROM tva_tri WHERE id_society = ' . $society_id);
        }
        //delete all Salarier for this society if Salarier is not selected anymore
        if (!in_array("Salarier", $choices)) {
            $db->query('DELETE FROM salarier WHERE id_society = ' . $society_id);
        }
        //delete all Foncier for this society if Foncier is not selected anymore
        if (!in_array("Foncier", $choices)) {
            $db->query('DELETE FROM foncier WHERE id_society = ' . $society_id);
        }
        //delete all I_S for this society if IS is not selected anymore
        if (!in_array("IS", $choices)) {
            $db->query('DELETE FROM i_s WHERE id_society = ' . $society_id);
        }
        //delete all Tp for this society if Tp is not selected anymore
        if (!in_array("TP", $choices)) {
            $db->query('DELETE FROM tp WHERE id_society = ' . $society_id);
        }
        //delete all Cnss for this society if Cnss is not selected anymore
        if (!in_array("CNSS", $choices)) {
            $db->query('DELETE FROM cnss WHERE id_society = ' . $society_id);
        }
        //delete all Tc for this society if Tc is not selected anymore
        if (!in_array("TC", $choices)) {
            $db->query('DELETE FROM tc WHERE id_society = ' . $society_id);
        }

        foreach ($choices as $choice) {
            //add tva_mens if it is selected
            if ($choice == "TVA_Mens") {
                //delete tva from tva_tri table
                $db->query('DELETE FROM tva_tri WHERE id_society = ' . $society_id);

                //check if tva already exists
                $tva_mens = $db->query('SELECT count(*) FROM tva_mens WHERE id_society = ' . $society_id)->fetchColumn();

                if ($tva_mens == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO tva_mens (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add tva_tri if it is selected
            elseif ($choice == "TVA_Tri") {
                //delete tva from tva_mens table
                $db->query('DELETE FROM tva_mens WHERE id_society = ' . $society_id);

                //check if tva already exists
                $tva_tri = $db->query('SELECT count(*) FROM tva_tri WHERE id_society = ' . $society_id)->fetchColumn();


                if ($tva_tri == 0) {
                    for ($i = 1; $i < 5; $i++) {
                        $db->query('INSERT INTO tva_tri (id_society, trimester, year ,paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add Salarier if it is selected
            if ($choice == "Salarier") {
                //check if Salarier already exists
                $salarier = $db->query('SELECT count(*) FROM salarier WHERE id_society = ' . $society_id)->fetchColumn();
                if ($salarier == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO salarier (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add Foncier if it is selected
            if ($choice == "Foncier") {
                //check if Foncier already exists
                $foncier = $db->query('SELECT count(*) FROM foncier WHERE id_society = ' . $society_id)->fetchColumn();
                if ($foncier == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO foncier (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add CS if it is selected
            if ($choice == "CS") {
                //check if cs already exists
                $cs = $db->query('SELECT count(*) FROM cs WHERE id_society = ' . $society_id)->fetchColumn();
                if ($cs == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO cs (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add IS if it is selected
            if ($choice == "IS") {

                //check if IS already exists
                $is = $db->query('SELECT count(*) FROM i_s WHERE id_society = ' . $society_id)->fetchColumn();


                if ($is == 0) {
                    for ($i = 1; $i < 5; $i++) {
                        $db->query('INSERT INTO i_s (id_society, trimester, year ,paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add TP if it is selected
            if ($choice == "TP") {
                //check if TP already exists
                $tp = $db->query('SELECT count(*) FROM tp WHERE id_society = ' . $society_id)->fetchColumn();
                if ($tp == 0) {
                    $db->query('INSERT INTO tp (id_society, year ,paied, notified) VALUES ("' . $society_id . '", "' . $year . '", 0, 0)');
                }
            }

            //add CNSS if it is selected
            if ($choice == "CNSS") {
                //check if CNSS already exists
                $cnss = $db->query('SELECT count(*) FROM cnss WHERE id_society = ' . $society_id)->fetchColumn();
                if ($cnss == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO cnss (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add TC if it is selected
            if ($choice == "TC") {
                //check if TC already exists
                $tc = $db->query('SELECT count(*) FROM tc WHERE id_society = ' . $society_id)->fetchColumn();
                if ($tc == 0) {
                    $db->query('INSERT INTO tc (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $_POST['tc_month'] . '", "' . $year . '", 0, 0)');
                } else {
                    $db->query('UPDATE tc SET month = "' . $_POST['tc_month'] . '" WHERE id_society = ' . $society_id);
                }
            }
        }
        header('Location: admin-society-manage.php');
    } else {
        header('Location: add-society.php?error=society&id=' . $society_id);
    }
}

//if submit is clicked, add the society with the id in url
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    //check if society name already exists
    $society = $db->query('SELECT count(*) FROM society WHERE name = "' . $name . '"')->fetchColumn();
    if ($society == 0) {

        $db->query('INSERT INTO society (name) VALUES ("' . $name . '")');
        $society_id = $db->query('SELECT id FROM society WHERE name = "' . $name . '"')->fetch()[0];
        $choices = $_POST['choices'];

        foreach ($choices as $choice) {
            //add tva if it is selected
            if ($choice == "TVA_Mens") {
                for ($i = 1; $i < 13; $i++) {
                    $db->query('INSERT INTO tva_mens (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                }
            }

            //add tva_tri if it is selected
            elseif ($choice == "TVA_Tri") {
                for ($i = 1; $i < 5; $i++) {
                    $db->query('INSERT INTO tva_tri (id_society, trimester, year ,paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                }
            }

            //add Salarier if it is selected
            if ($choice == "Salarier") {
                //check if Salarier already exists
                $salarier = $db->query('SELECT count(*) FROM salarier WHERE id_society = ' . $society_id)->fetchColumn();
                if ($salarier == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO salarier (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add Foncier if it is selected
            if ($choice == "Foncier") {
                //check if Foncier already exists
                $foncier = $db->query('SELECT count(*) FROM foncier WHERE id_society = ' . $society_id)->fetchColumn();
                if ($foncier == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO foncier (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add CS if it is selected
            if ($choice == "CS") {
                //check if CS already exists
                $cs = $db->query('SELECT count(*) FROM cs WHERE id_society = ' . $society_id)->fetchColumn();
                if ($cs == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO cs (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add IS if it is selected
            if ($choice == "IS") {
                for ($i = 1; $i < 5; $i++) {
                    $db->query('INSERT INTO i_s (id_society, trimester, year ,paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                }
            }

            //add TP if it is selected
            if ($choice == "TP") {
                //check if TP already exists
                $tp = $db->query('SELECT count(*) FROM tp WHERE id_society = ' . $society_id)->fetchColumn();
                if ($tp == 0) {
                    $db->query('INSERT INTO tp (id_society, year ,paied, notified) VALUES ("' . $society_id . '", "' . $year . '", 0, 0)');
                }
            }

            //add CNSS if it is selected
            if ($choice == "CNSS") {
                //check if CNSS already exists
                $cnss = $db->query('SELECT count(*) FROM cnss WHERE id_society = ' . $society_id)->fetchColumn();
                if ($cnss == 0) {
                    for ($i = 1; $i < 13; $i++) {
                        $db->query('INSERT INTO cnss (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $i . '", "' . $year . '", 0, 0)');
                    }
                }
            }

            //add TC if it is selected
            if ($choice == "TC") {
                //check if TC already exists
                $tc = $db->query('SELECT count(*) FROM tc WHERE id_society = ' . $society_id)->fetchColumn();
                if ($tc == 0) {
                    $db->query('INSERT INTO tc (id_society, month, year , paied, notified) VALUES ("' . $society_id . '", "' . $_POST['tc_month'] . '", "' . $year . '", 0, 0)');
                }
            }
        }

        header('Location: admin-society-manage.php');
    } else {
        header('Location: add-society.php?error=society');
    }
}
?>

<script>
    function validateForm() {
        //check if name is empty
        if (document.getElementById("name").value == "") {
            alert("Veuillez entrer un nom de société");
            return false;
        }

        //check if at least one choice is selected
        var checkboxes = document.getElementsByName('choices[]');
        var checked = false;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checked = true;
                break;
            }
        }
        if (!checked) {
            alert('Veuillez sélectionner au moins un choix');
            return false;
        }
        return true;
    }

    function displaySelect() {
        var checkBox = document.getElementById("TC");
        var select = document.getElementById("tc_month");
        if (checkBox.checked == true){
            select.style.display = "block";
        } else {
            select.style.display = "none";
        }
    }

    onload = function() {
        displaySelect();
    }
</script>

<main>
    <div class="container">
        <div class="header">
            <h1>Ajouter Société</h1>
        </div>
        <div class="content">
            <form class="add-society" action="add-society.php" method="post" onsubmit="if(this.submitted) return validateForm();">
                <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) {echo $_GET['id'];} ?>">
                <?php
                if (isset($_GET['id'])) {echo '<input type="hidden" name="id" value="' . $_GET['id'] . '">';}
                ?>
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php if (isset($_GET['id'])) echo $society['name'];?>">
                </div>
                <div class="form-group">
                    <h2>Choix :</h2>
                </div>
                <div class="choices">
                    <div class="choice-container">
                        <h3>DGI :</h3>
                        <div class="choice">
                            <h4>TVA :</h4>
                            <div class="choice-group">
                                <div class="form-choice">
                                    <input type="radio" name="choices[]" id="TVA_Mens" value="TVA_Mens" <?php if (isset($_GET['id'])) {if ($tva_mens != 0) {echo 'checked';}} ?>>
                                    <label for="TVA_Mens">TVA Mensuel</label>
                                    <label onclick="document.getElementById('TVA_Mens').checked = false" style="color:red">&#10006;</label>
                                </div>
                                <div class="form-choice">
                                    <input type="radio" name="choices[]" id="TVA_Tri" value="TVA_Tri" <?php if (isset($_GET['id'])) {if ($tva_tri != 0) {echo 'checked';}} ?> onclick="">
                                    <label for="TVA_Tri">TVA Trimistriel</label>
                                    <label onclick="document.getElementById('TVA_Tri').checked = false" style="color:red">&#10006;</label>
                                </div>
                            </div>
                            <h4>IR :</h4>
                            <div class="choice-group">
                                <div class="form-choice">
                                    <input type="checkbox" name="choices[]" id="Salarier" value="Salarier" <?php if (isset($_GET['id']) && $salarier != 0) {echo 'checked';} ?>>
                                    <label for="Salarier">Salarier</label>
                                </div>
                                <div class="form-choice">
                                    <input type="checkbox" name="choices[]" id="Foncier" value="Foncier" <?php if (isset($_GET['id']) && $foncier != 0) {echo 'checked';} ?>>
                                    <label for="Foncier">Foncier</label>
                                </div>
                                <div class="form-choice">
                                    <input type="checkbox" name="choices[]" id="CS" value="CS" <?php if (isset($_GET['id']) && $cs != 0) {echo 'checked';} ?>>
                                    <label for="CS">Contribution Sociale</label>
                                </div>
                            </div>
                            <h4>IS :</h4>
                            <div class="choice-group">
                                <div class="form-choice">
                                    <input type="checkbox" name="choices[]" id="IS" value="IS" <?php if (isset($_GET['id']) && $is != 0) {echo 'checked';} ?>>
                                    <label for="IS">Impôt Société</label>
                                </div>
                            </div>
                            <h4>TP :</h4>
                            <div class="choice-group">
                                <div class="form-choice">
                                    <input type="checkbox" name="choices[]" id="TP" value="TP" <?php if (isset($_GET['id']) && $tp != 0) {echo 'checked';} ?>>
                                    <label for="TP">TP</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="choice-container">
                        <h3>CNSS :</h3>
                        <div class="choice">
                            <div class="form-choice">
                                <input type="checkbox" name="choices[]" id="CNSS" value="CNSS" <?php if (isset($_GET['id']) && $cnss != 0) {echo 'checked';} ?>>
                                <label for="CNSS">CNSS</label>
                            </div>
                        </div>
                    </div>
                    <div class="choice-container">
                        <h3>TC :</h3>
                        <div class="choice">
                            <div class="form-choice">
                                <input type="checkbox" name="choices[]" id="TC" value="TC" onclick="displaySelect()" <?php if (isset($_GET['id']) && $tc != 0) {echo 'checked';} ?>>
                                <label for="TC">TC</label>
                                <select name="tc_month" id="tc_month">
                                    <option disabled selected value> -- select an option -- </option>
                                    <option value="1" <?php if (isset($_GET['id']) && $tc_month == 1) {echo 'selected="selected"';} ?>>1</option>
                                    <option value="2" <?php if (isset($_GET['id']) && $tc_month == 2) {echo 'selected="selected"';} ?>>2</option>
                                    <option value="3" <?php if (isset($_GET['id']) && $tc_month == 3) {echo 'selected="selected"';} ?>>3</option>
                                    <option value="4" <?php if (isset($_GET['id']) && $tc_month == 4) {echo 'selected="selected"';} ?>>4</option>
                                    <option value="5" <?php if (isset($_GET['id']) && $tc_month == 5) {echo 'selected="selected"';} ?>>5</option>
                                    <option value="6" <?php if (isset($_GET['id']) && $tc_month == 6) {echo 'selected="selected"';} ?>>6</option>
                                    <option value="7" <?php if (isset($_GET['id']) && $tc_month == 7) {echo 'selected="selected"';} ?>>7</option>
                                    <option value="8" <?php if (isset($_GET['id']) && $tc_month == 8) {echo 'selected="selected"';} ?>>8</option>
                                    <option value="9" <?php if (isset($_GET['id']) && $tc_month == 9) {echo 'selected="selected"';} ?>>9</option>
                                    <option value="10" <?php if (isset($_GET['id']) && $tc_month == 10) {echo 'selected="selected"';} ?>>10</option>
                                    <option value="11" <?php if (isset($_GET['id']) && $tc_month == 11) {echo 'selected="selected"';} ?>>11</option>
                                    <option value="12" <?php if (isset($_GET['id']) && $tc_month == 12) {echo 'selected="selected"';} ?>>12</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="register-div">
                    <?php
                    if (isset($_GET['id'])) {
                        echo '<button id="submit" type="submit" class="submit-btn" name="update" onclick="this.form.submitted=true">Mettre à jour</button>';
                        echo '<button type="submit" name="delete" class="delete-btn" onclick="this.form.submitted=false">Supprimer</button>';
                    } else {
                        echo '<button type="submit" class="submit-btn" name="submit" onclick="this.form.submitted=true">Enregistrer</button>';
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
</main>