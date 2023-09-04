<?php
include '../includes/header-user.php';
//check if user has this society
$user_society = $db->query('SELECT * FROM user_society WHERE id_user = ' . $_SESSION['id'] . ' AND id_society = ' . $_GET['id']);
if ($user_society->rowCount() == 0)
    header('Location: ../index.php');


//get society name
$society = $db->query('SELECT * FROM society where id = ' . $_GET['id'])->fetch();
$name = $society['name'];

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

//tva mens file
function tva_file()
{
    global $db;
    //upload_mens file
    if (isset($_POST['upload_mens'])) {
        $tva_id = $_POST['tva_id'];
        $tva_info = $db->query('SELECT * FROM tva_mens where id = ' . $tva_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tva_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'Tva_mens_' . $society['name'] . '_' . $tva_info['month'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['notified'])) {
                    $db->query('UPDATE tva_mens SET notified = false WHERE id = ' . $tva_id);
                } else {
                    $db->query('UPDATE tva_mens SET notified = true WHERE id = ' . $tva_id);
                }
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE tva_mens SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $tva_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE tva_mens SET notified = false WHERE id = ' . $tva_id);
            } else {
                $db->query('UPDATE tva_mens SET notified =true WHERE id = ' . $tva_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_mens the file
    if (isset($_POST['update_mens'])) {
        unlink($_POST['old_file']);
        $tva_id = $_POST['tva_id'];
        $tva_info = $db->query('SELECT * FROM tva_mens where id = ' . $tva_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tva_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name =  'Tva_mens_' . $society['name'] . '_' . $tva_info['month'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE tva_mens SET notified = false WHERE id = ' . $tva_id);
            } else {
                $db->query('UPDATE tva_mens SET notified =true WHERE id = ' . $tva_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE tva_mens SET file = "' . $file_name . '" WHERE id = ' . $tva_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_mens the file
    if (isset($_POST['delete_mens'])) {
        unlink($_POST['old_file']);
        $tva_id = $_POST['tva_id'];
        $db->query('UPDATE tva_mens SET paied = 0, file = NULL WHERE id = ' . $tva_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//tva tri file
function tva_tri()
{
    global $db;
    //upload_tri file
    if (isset($_POST['upload_tri'])) {
        $tva_id = $_POST['tva_id'];
        $tva_info = $db->query('SELECT * FROM tva_tri where id = ' . $tva_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tva_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'Tva_tri_' . $society['name'] . '_' . $tva_info['trimester'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['notified'])) {
                    $db->query('UPDATE tva_tri SET notified = false WHERE id = ' . $tva_id);
                } else {
                    $db->query('UPDATE tva_tri SET notified =true WHERE id = ' . $tva_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE tva_tri SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $tva_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE tva_tri SET notified = false WHERE id = ' . $tva_id);
            } else {
                $db->query('UPDATE tva_tri SET notified =true WHERE id = ' . $tva_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_trithe file
    if (isset($_POST['update_tri'])) {
        unlink($_POST['old_file']);
        $tva_id = $_POST['tva_id'];
        $tva_info = $db->query('SELECT * FROM tva_tri where id = ' . $tva_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tva_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'Tva_tri_' . $society['name'] . '_' . $tva_info['trimester'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE tva_tri SET notified = false WHERE id = ' . $tva_id);
            } else {
                $db->query('UPDATE tva_tri SET notified = true WHERE id = ' . $tva_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE tva_tri SET file = "' . $file_name . '" WHERE id = ' . $tva_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_tri the file
    if (isset($_POST['delete_tri'])) {
        unlink($_POST['old_file']);
        $tva_id = $_POST['tva_id'];
        $db->query('UPDATE tva_tri SET paied = 0, file = NULL WHERE id = ' . $tva_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//salarier file
function salarier_file()
{
    global $db;
    //upload_salarier file
    if (isset($_POST['upload_salarier'])) {
        $ir_id = $_POST['ir_id'];
        $salarier_info = $db->query('SELECT * FROM salarier where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $salarier_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'Salarier_' . $society['name'] . '_' . $salarier_info['month'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['salarier_notified'])) {
                    $db->query('UPDATE salarier SET notified = false WHERE id = ' . $ir_id);
                } else {
                    $db->query('UPDATE salarier SET notified = true WHERE id = ' . $ir_id);
                }
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE salarier SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $ir_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['salarier_notified'])) {
                $db->query('UPDATE salarier SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE salarier SET notified = true WHERE id = ' . $ir_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_salarier the file
    if (isset($_POST['update_salarier'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $salarier_info = $db->query('SELECT * FROM salarier where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $salarier_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'Salarier_' . $society['name'] . '_' . $salarier_info['month'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['salarier_notified'])) {
                $db->query('UPDATE salarier SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE salarier SET notified = true WHERE id = ' . $ir_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE salarier SET file = "' . $file_name . '" WHERE id = ' . $ir_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_salarier the file
    if (isset($_POST['delete_salarier'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $db->query('UPDATE salarier SET paied = 0, file = NULL WHERE id = ' . $ir_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//foncier file
function foncier_file()
{
    global $db;
    //upload_foncier file
    if (isset($_POST['upload_foncier'])) {
        $ir_id = $_POST['ir_id'];
        $foncier_info = $db->query('SELECT * FROM foncier where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $foncier_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'Foncier_' . $society['name'] . '_' . $foncier_info['month'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['foncier_notified'])) {
                    $db->query('UPDATE foncier SET notified = false WHERE id = ' . $ir_id);
                } else {
                    $db->query('UPDATE foncier SET notified =true WHERE id = ' . $ir_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE foncier SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $ir_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['foncier_notified'])) {
                $db->query('UPDATE foncier SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE foncier SET notified =true WHERE id = ' . $ir_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_foncier the file
    if (isset($_POST['update_foncier'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $foncier_info = $db->query('SELECT * FROM foncier where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $foncier_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'Foncier_' . $society['name'] . '_' . $foncier_info['month'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE foncier SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE foncier SET notified =true WHERE id = ' . $ir_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE foncier SET file = "' . $file_name . '" WHERE id = ' . $ir_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_foncier the file
    if (isset($_POST['delete_foncier'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $db->query('UPDATE foncier SET paied = 0, file = NULL WHERE id = ' . $ir_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//cs file
function cs_file()
{
    global $db;
    //upload_cs file
    if (isset($_POST['upload_cs'])) {
        $ir_id = $_POST['ir_id'];
        $cs_info = $db->query('SELECT * FROM cs where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $cs_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'CS_' . $society['name'] . '_' . $cs_info['month'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['cs_notified'])) {
                    $db->query('UPDATE cs SET notified = false WHERE id = ' . $ir_id);
                } else {
                    $db->query('UPDATE cs SET notified =true WHERE id = ' . $ir_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE cs SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $ir_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['cs_notified'])) {
                $db->query('UPDATE cs SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE cs SET notified =true WHERE id = ' . $ir_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_cs the file
    if (isset($_POST['update_cs'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $cs_info = $db->query('SELECT * FROM cs where id = ' . $ir_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $cs_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'CS_' . $society['name'] . '_' . $cs_info['month'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE cs SET notified = false WHERE id = ' . $ir_id);
            } else {
                $db->query('UPDATE cs SET notified =true WHERE id = ' . $ir_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE cs SET file = "' . $file_name . '" WHERE id = ' . $ir_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_cs the file
    if (isset($_POST['delete_cs'])) {
        unlink($_POST['old_file']);
        $ir_id = $_POST['ir_id'];
        $db->query('UPDATE cs SET paied = 0, file = NULL WHERE id = ' . $ir_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//i_s file
function isfile()
{
    global $db;
    //upload_is file
    if (isset($_POST['upload_is'])) {
        $is_id = $_POST['is_id'];
        $is_info = $db->query('SELECT * FROM i_s where id = ' . $is_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $is_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name =  'IS_' . $society['name'] . '_' . $is_info['trimester'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['is_notified'])) {
                    $db->query('UPDATE i_s SET notified = false WHERE id = ' . $is_id);
                } else {
                    $db->query('UPDATE i_s SET notified = true WHERE id = ' . $is_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE i_s SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $is_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['is_notified'])) {
                $db->query('UPDATE i_s SET notified = false WHERE id = ' . $is_id);
            } else {
                $db->query('UPDATE i_s SET notified =true WHERE id = ' . $is_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_is the file
    if (isset($_POST['update_is'])) {
        unlink($_POST['old_file']);
        $is_id = $_POST['is_id'];
        $is_info = $db->query('SELECT * FROM i_s where id = ' . $is_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $is_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'IS_' . $society['name'] . '_' . $is_info['trimester'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['is_notified'])) {
                $db->query('UPDATE i_s SET notified = false WHERE id = ' . $is_id);
            } else {
                $db->query('UPDATE i_s SET notified = true WHERE id = ' . $is_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE i_s SET file = "' . $file_name . '" WHERE id = ' . $is_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_is the file
    if (isset($_POST['delete_is'])) {
        unlink($_POST['old_file']);
        $is_id = $_POST['is_id'];
        $db->query('UPDATE i_s SET paied = 0, file = NULL WHERE id = ' . $is_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//tp file
function tp_file()
{
    global $db;
    //upload_tp file
    if (isset($_POST['upload_tp'])) {
        $tp_id = $_POST['tp_id'];
        $tp_info = $db->query('SELECT * FROM tp where id = ' . $tp_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tp_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name =  'TP_' . $society['name'] . '_' . $tp_info['trimester'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['tp_notified'])) {
                    $db->query('UPDATE tp SET notified = false WHERE id = ' . $tp_id);
                } else {
                    $db->query('UPDATE tp SET notified = true WHERE id = ' . $tp_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE tp SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $tp_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['tp_notified'])) {
                $db->query('UPDATE tp SET notified = false WHERE id = ' . $tp_id);
            } else {
                $db->query('UPDATE tp SET notified =true WHERE id = ' . $tp_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_tp the file
    if (isset($_POST['update_tp'])) {
        unlink($_POST['old_file']);
        $tp_id = $_POST['tp_id'];
        $tp_info = $db->query('SELECT * FROM tp where id = ' . $tp_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tp_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'TP_' . $society['name'] . '_' . $tp_info['trimester'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['tp_notified'])) {
                $db->query('UPDATE tp SET notified = false WHERE id = ' . $tp_id);
            } else {
                $db->query('UPDATE tp SET notified = true WHERE id = ' . $tp_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE tp SET file = "' . $file_name . '" WHERE id = ' . $tp_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_tp the file
    if (isset($_POST['delete_tp'])) {
        unlink($_POST['old_file']);
        $tp_id = $_POST['tp_id'];
        $db->query('UPDATE tp SET paied = 0, file = NULL WHERE id = ' . $tp_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//cnss file
function cnss_file()
{
    global $db;
    //upload_cnss file
    if (isset($_POST['upload_cnss'])) {
        $cnss_id = $_POST['cnss_id'];
        $cnss_info = $db->query('SELECT * FROM cnss where id = ' . $cnss_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $cnss_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name = 'CNSS_' . $society['name'] . '_' . $cnss_info['month'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['cnss_notified'])) {
                    $db->query('UPDATE cnss SET notified = false WHERE id = ' . $cnss_id);
                } else {
                    $db->query('UPDATE cnss SET notified = true WHERE id = ' . $cnss_id);
                }
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE cnss SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $cnss_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE cnss SET notified = false WHERE id = ' . $cnss_id);
            } else {
                $db->query('UPDATE cnss SET notified = true WHERE id = ' . $cnss_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_cnss the file
    if (isset($_POST['update_cnss'])) {
        unlink($_POST['old_file']);
        $cnss_id = $_POST['cnss_id'];
        $cnss_info = $db->query('SELECT * FROM cnss where id = ' . $cnss_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $cnss_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name =  'CNSS_' . $society['name'] . '_' . $cnss_info['month'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['notified'])) {
                $db->query('UPDATE cnss SET notified = false WHERE id = ' . $cnss_id);
            } else {
                $db->query('UPDATE cnss SET notified =true WHERE id = ' . $cnss_id);
            }
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE cnss SET file = "' . $file_name . '" WHERE id = ' . $cnss_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_cnss the file
    if (isset($_POST['delete_cnss'])) {
        unlink($_POST['old_file']);
        $cnss_id = $_POST['cnss_id'];
        $db->query('UPDATE cnss SET paied = 0, file = NULL WHERE id = ' . $cnss_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

//tc file
function tc_file()
{
    global $db;
    //upload_tc file
    if (isset($_POST['upload_tc'])) {
        $tc_id = $_POST['tc_id'];
        $tc_info = $db->query('SELECT * FROM tc where id = ' . $tc_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tc_info['id_society'])->fetch();
        //check if there is a file
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            $file_error = $file['error'];
            $file_ext = explode('.', $file_name);
            $file_name = $file_ext[0];
            $file_ext = strtolower(end($file_ext));
            $file_name =  'TC_' . $society['name'] . '_' . $tc_info['trimester'] . '.' . $file_ext;
            $file_destination = '../uploads/' . $file_name;

            if ($file_error == 1) {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_size == 0) {
                if (!isset($_POST['tc_notified'])) {
                    $db->query('UPDATE tc SET notified = false WHERE id = ' . $tc_id);
                } else {
                    $db->query('UPDATE tc SET notified = true WHERE id = ' . $tc_id);
                }

                header('Location: user-society.php?id=' . $_GET['id']);
                return;
            }

            if ($file_error == 0) {
                if ($file_size <= 10485760) {
                    if (in_array($file_ext, array('pdf'))) {
                        if (move_uploaded_file($file_tmp_name, $file_destination)) {
                            $db->query('UPDATE tc SET paied = 1 , notified = 1, file = "' . $file_name . '" WHERE id = ' . $tc_id);
                            header('Location: user-society.php?id=' . $_GET['id']);
                        } else {
                            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                        }
                    } else {
                        echo '<script>alert("Votre fichier doit être un PDF")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier est trop volumineux")</script>';
                }
            } else {
                echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

                header('Location: user-society.php?id=' . $_GET['id']);
            }
        } else {
            if (!isset($_POST['tc_notified'])) {
                $db->query('UPDATE tc SET notified = false WHERE id = ' . $tc_id);
            } else {
                $db->query('UPDATE tc SET notified =true WHERE id = ' . $tc_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //update_tc the file
    if (isset($_POST['update_tc'])) {
        unlink($_POST['old_file']);
        $tc_id = $_POST['tc_id'];
        $tc_info = $db->query('SELECT * FROM tc where id = ' . $tc_id)->fetch();
        $society = $db->query('SELECT * FROM society where id = ' . $tc_info['id_society'])->fetch();
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_ext = explode('.', $file_name);
        $file_name = $file_ext[0];
        $file_ext = strtolower(end($file_ext));
        $file_name = 'TC_' . $society['name'] . '_' . $tc_info['trimester'] . '.' . $file_ext;
        $file_destination = '../uploads/' . $file_name;

        if ($file_error == 1) {
            echo '<script>alert("Votre fichier est trop volumineux")</script>';
            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_size == 0) {
            if (!isset($_POST['tc_notified'])) {
                $db->query('UPDATE tc SET notified = false WHERE id = ' . $tc_id);
            } else {
                $db->query('UPDATE tc SET notified = true WHERE id = ' . $tc_id);
            }

            header('Location: user-society.php?id=' . $_GET['id']);
            return;
        }

        if ($file_error == 0) {
            if (
                $file_size <= 10485760
            ) {
                if (in_array($file_ext, array('pdf'))) {
                    if (move_uploaded_file($file_tmp_name, $file_destination)) {
                        $db->query('UPDATE tc SET file = "' . $file_name . '" WHERE id = ' . $tc_id);
                        header('Location: user-society.php?id=' . $_GET['id']);
                    } else {
                        echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';
                    }
                } else {
                    echo '<script>alert("Votre fichier doit être un PDF")</script>';
                }
            } else {
                echo '<script>alert("Votre fichier est trop volumineux")</script>';
            }
        } else {
            echo '<script>alert("Erreur lors de l\'upload du fichier")</script>';

            header('Location: user-society.php?id=' . $_GET['id']);
        }
    }

    //delete_tc the file
    if (isset($_POST['delete_tc'])) {
        unlink($_POST['old_file']);
        $tc_id = $_POST['tc_id'];
        $db->query('UPDATE tc SET paied = 0, file = NULL WHERE id = ' . $tc_id);
        header('Location: user-society.php?id=' . $_GET['id']);
    }
}

tva_file();
tva_tri();
salarier_file();
foncier_file();
cs_file();
isfile();
tp_file();
cnss_file();
tc_file();
?>

<main>
    <div class="container">
        <div class="header">
            <h1><?php echo $name ?></h1>
        </div>
        <div class="content">
            <?php
            if ($isTva_mens || $isTva_tri || $isSalarier || $isFoncier || $isCs || $isIs) {
                echo '<h2>DGI</h2>';
            }
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
                                echo 'checked>
                                        <label for="notified">Client notifié</label>';
                            } else {
                                echo '>
                                        <label for="notified">Client non notifié</label>';
                            }
                            echo '
                                    </div>
                                <input type="submit" name="upload_mens" value="Confirm">
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($tva_month['paied'] == 0) {
                                if ($tva_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified" checked>
                                            <label for="notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_mens" value="Confirm">
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified">
                                            <label for="notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_mens" value="Confirm">
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
                                        <a href="../download.php?id=' . $tva_month['id'] . '" class="submit-btn">' . $tva_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_mens" value="Confirm">
                                            <input type="submit" name="delete_mens" value="Delete">
                                        </div>
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
                                            <input type="checkbox" id="notified" name="notified" checked>
                                            <label for="notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified">
                                            <label for="notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <input type="submit" name="upload_mens" value="Confirm">
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
                                        <a href="../download.php?id=' . $tva_month['id'] . '" class="submit-btn">' . $tva_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $tva_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_mens" value="Confirm">
                                            <input type="submit" name="delete_mens" value="Delete">
                                        </div>
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
                //select data of this year
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
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified">
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
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
                                                    <input type="checkbox" id="notified" name="notified" checked>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified">
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 1) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo '>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified">
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
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
                                                    <input type="checkbox" id="notified" name="notified" checked>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified">
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 4) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                                <input type="checkbox" id="notified" name="notified" checked>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified">
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
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
                                                    <input type="checkbox" id="notified" name="notified" checked>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified">
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 7) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo '>
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                if ($tva_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($tva_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified" checked>
                                                <label for="notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="notified" name="notified">
                                                <label for="notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
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
                                                    <input type="checkbox" id="notified" name="notified" checked>
                                                    <label for="notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="notified" name="notified">
                                                    <label for="notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_tri" value="Confirm">
                                                <input type="submit" name="delete_tri" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 10) {
                                echo '<input type="hidden" name="tva_id" value="' . $tva_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="notified" name="notified"
                                    ';
                                if ($tva_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_tri" value="Confirm">
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
                        //salarier id
                        echo '<input type="hidden" name="ir_id" value="' . $salarier_month['id'] . '">';

                        //upload the salarier file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="salarier_notified" name="salarier_notified"';
                            if ($salarier_month['notified'] == 1) {
                                echo 'checked>
                                    <label for="salarier_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="salarier_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                <input type="submit" name="upload_salarier" value="Confirm">
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($salarier_month['paied'] == 0) {
                                if ($salarier_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_salarier" value="Confirm">
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified">
                                            <label for="salarier_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_salarier" value="Confirm">
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
                                        <a href="../download.php?id=' . $salarier_month['id'] . '" class="submit-btn">' . $salarier_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $salarier_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_salarier" value="Confirm">
                                            <input type="submit" name="delete_salarier" value="Delete">
                                        </div>
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
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified" checked>
                                            <label for="salarier_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="salarier_notified" name="salarier_notified">
                                            <label for="salarier_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <input type="submit" name="upload_salarier" value="Confirm">
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
                                        <a href="../download.php?id=' . $salarier_month['id'] . '" class="submit-btn">' . $salarier_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $salarier_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_salarier" value="Confirm">
                                            <input type="submit" name="delete_salarier" value="Delete">
                                        </div>
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
                        //foncier id
                        echo '<input type="hidden" name="ir_id" value="' . $foncier_month['id'] . '">';

                        //upload the foncier file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="foncier_notified" name="foncier_notified"';
                            if ($foncier_month['notified'] == 1) {
                                echo 'checked>
                                    <label for="foncier_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="foncier_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                <input type="submit" name="upload_foncier" value="Confirm">
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($foncier_month['paied'] == 0) {
                                if ($foncier_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_foncier" value="Confirm">
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified">
                                            <label for="foncier_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_foncier" value="Confirm">
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
                                        <a href="../download.php?id=' . $foncier_month['id'] . '" class="submit-btn">' . $foncier_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $foncier_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_foncier" value="Confirm">
                                            <input type="submit" name="delete_foncier" value="Delete">
                                        </div>
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
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified" checked>
                                            <label for="foncier_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="foncier_notified" name="foncier_notified">
                                            <label for="foncier_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <input type="submit" name="upload_foncier" value="Confirm">
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
                                        <a href="../download.php?id=' . $foncier_month['id'] . '" class="submit-btn">' . $foncier_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $foncier_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_foncier" value="Confirm">
                                            <input type="submit" name="delete_foncier" value="Delete">
                                        </div>
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
                        //cs id
                        echo '<input type="hidden" name="ir_id" value="' . $cs_month['id'] . '">';

                        //upload the cs file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                <div class="payement-month-container">
                                    <input type="checkbox" id="cs_notified" name="cs_notified"';
                            if ($cs_month['notified'] == 1) {
                                echo 'checked>
                                    <label for="cs_notified">Client notifié</label>';
                            } else {
                                echo '>
                                    <label for="cs_notified">Client non notifié</label>';
                            }
                            echo '
                                </div>
                                <input type="submit" name="upload_cs" value="Confirm">
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($cs_month['paied'] == 0) {
                                if ($cs_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked>
                                            <label for="cs_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_cs" value="Confirm">
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified">
                                            <label for="cs_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_cs" value="Confirm">
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
                                        <a href="../download.php?id=' . $cs_month['id'] . '" class="submit-btn">' . $cs_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $cs_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_cs" value="Confirm">
                                            <input type="submit" name="delete_cs" value="Delete">
                                        </div>
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
                                            <input type="checkbox" id="cs_notified" name="cs_notified" checked>
                                            <label for="cs_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cs_notified" name="cs_notified">
                                            <label for="cs_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <input type="submit" name="upload_cs" value="Confirm">
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
                                        <a href="../download.php?id=' . $cs_month['id'] . '" class="submit-btn">' . $cs_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $cs_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_cs" value="Confirm">
                                            <input type="submit" name="delete_cs" value="Delete">
                                        </div>
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
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                <label for="is_notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified">
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 1) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified">
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 1) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '>
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
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
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                <label for="is_notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified">
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 4) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified">
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 4) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
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
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                <label for="is_notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified">
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 7) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified">
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 7) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '>
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
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
                            } elseif ($date->format('m') == 12 && $date->format('j') > 20) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    echo '
                                        <td class="payement-month-alert">
                                        <div class="payement-content">
                                        ';
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                <label for="is_notified">client notifié</label>
                                            </div>';
                                    } else {
                                        echo '
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified">
                                                <label for="is_notified">client non notifié</label>
                                            </div>';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') > 10) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                if ($is_month['paied'] == 0) {
                                    if ($is_month['notified'] == 1) {
                                        echo '
                                            <td class="payement-month">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified" checked>
                                                    <label for="is_notified">client notifié</label>
                                                </div>
                                            ';
                                    } else {
                                        echo '
                                            <td class="payement-month-alert">
                                            <div class="payement-content">
                                                <div class="payement-month-container">
                                                    <input type="checkbox" id="is_notified" name="is_notified">
                                                    <label for="is_notified">client non notifié</label>
                                                </div>
                                            ';
                                    }
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
                                        </div>
                                        </td>';
                                } else {
                                    echo '
                                        <td class="paye">
                                        <div class="payement-content">
                                            <div class="payement-month-container">
                                                <input type="checkbox" id="is_notified" name="is_notified" checked disabled>
                                                <label for="is_notified">client notifié</label>
                                            </div>
                                            <a href="../download.php?id=' . $is_month['id'] . '">' . $is_month['file'] . '</a>
                                            <input type="hidden" name="old_file" value="../uploads/' . $is_month['file'] . '">
                                            <div class="payement-month-container">
                                                <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                            <div class="payement-month-container">
                                                <input type="submit" name="update_is" value="Confirm">
                                                <input type="submit" name="delete_is" value="Delete">
                                            </div>
                                            </div>
                                        </td>';
                                }
                            } elseif ($date->format('m') == 10) {
                                echo '<input type="hidden" name="is_id" value="' . $is_month['id'] . '">';
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="is_notified" name="is_notified"
                                    ';
                                if ($is_month['notified'] == 1) {
                                    echo '
                                        checked>
                                        <label for="is_notified">client notifié</label>
                                        ';
                                } else {
                                    echo '
                                        <label for="is_notified">client non notifié</label>
                                        ';
                                }
                                echo '
                                        </div>
                                        <input type="submit" name="upload_is" value="Confirm">
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
                    if ($date->format('m') < 12) {
                        echo '<input type="hidden" name="tp_id" value="' . $tp_year['id'] . '">';
                        if ($tp_year['paied'] == 0) {
                            if ($tp_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified" checked>
                                            <label for="tp_notified">client notifié</label>
                                        </div>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified">
                                            <label for="tp_notified">client non notifié</label>
                                        </div>
                                    ';
                            }
                            echo '
                                <div class="payement-month-container">
                                    <input type="file" name="file" id="file" accept=".pdf">
                                </div>
                                <input type="submit" name="upload_tp" value="Confirm">
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
                                    <input type="hidden" name="old_file" value="../uploads/' . $tp_year['file'] . '">
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <div class="payement-month-container">
                                        <input type="submit" name="update_tp" value="Confirm">
                                        <input type="submit" name="delete_tp" value="Delete">
                                    </div>
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
                                            <input type="checkbox" id="tp_notified" name="tp_notified" checked>
                                            <label for="tp_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tp" value="Confirm">
                                    </div>
                                    </td>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tp_notified" name="tp_notified">
                                            <label for="tp_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tp" value="Confirm">
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
                                    <input type="hidden" name="old_file" value="../uploads/' . $tp_year['file'] . '">
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <div class="payement-month-container">
                                        <input type="submit" name="update_tp" value="Confirm">
                                        <input type="submit" name="delete_tp" value="Delete">
                                    </div>
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
                        //cnss id
                        echo '<input type="hidden" name="cnss_id" value="' . $cnss_month['id'] . '">';

                        //upload the cnss file
                        if ($date->format('j') < 5) {
                            echo '<td class="payement-month">
                                <div class="payement-content">
                                    <div class="payement-month-container">
                                        <input type="checkbox" id="cnss_notified" name="cnss_notified"';
                            if ($cnss_month['notified'] == 1) {
                                echo 'checked>
                                        <label for="cnss_notified">Client notifié</label>';
                            } else {
                                echo '>
                                        <label for="cnss_notified">Client non notifié</label>';
                            }
                            echo '
                                    </div>
                                <input type="submit" name="upload_cnss" value="Confirm">
                                </div>
                                </div>
                                </td>';
                        } elseif ($date->format('j') < 25) {
                            if ($cnss_month['paied'] == 0) {
                                if ($cnss_month['notified'] == 1) {
                                    echo '<td class="payement-month">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_cnss" value="Confirm">
                                        </td>';
                                } else {
                                    echo '<td class="payement-month-alert">
                                        <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified">
                                            <label for="cnss_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                            </div>
                                        <input type="submit" name="upload_cnss" value="Confirm">
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
                                        <a href="../download.php?id=' . $cnss_month['id'] . '" class="submit-btn">' . $cnss_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $cnss_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_cnss" value="Confirm">
                                            <input type="submit" name="delete_cnss" value="Delete">
                                        </div>
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
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified" checked>
                                            <label for="cnss_notified">client notifié</label>
                                        </div>';
                                } else {
                                    echo '
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="cnss_notified" name="cnss_notified">
                                            <label for="cnss_notified">client non notifié</label>
                                        </div>';
                                }
                                echo '
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <input type="submit" name="upload_cnss" value="Confirm">
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
                                        <a href="../download.php?id=' . $cnss_month['id'] . '" class="submit-btn">' . $cnss_month['file'] . '</a>
                                        <input type="hidden" name="old_file" value="../uploads/' . $cnss_month['file'] . '">
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="submit" name="update_cnss" value="Confirm">
                                            <input type="submit" name="delete_cnss" value="Delete">
                                        </div>
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

                //fetching data
                while ($tc_year = $tc->fetch()) {
                    $tc_month = new DateTime(strval($tc_year['year']) . '-' . strval($tc_year['month']) . '-' . $date->format('d'));
                    echo '<tr>';
                    echo '<th>' . $date->format('Y') . ' (A payé en ' . $tc_month->format('F') . ')</th>';
                    echo '</tr>';

                    $month = $tc_year['month'] + 5;
                    if ($month > 12) {
                        $month = $month - 12;
                    }


                    if ($date->format('m') < $month) {
                        echo '<input type="hidden" name="tc_id" value="' . $tc_year['id'] . '">';
                        if ($tc_year['paied'] == 0) {
                            if ($tc_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" checked>
                                            <label for="tc_notified">client notifié</label>
                                        </div>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified">
                                            <label for="tc_notified">client non notifié</label>
                                        </div>
                                    ';
                            }
                            echo '
                                <div class="payement-month-container">
                                    <input type="file" name="file" id="file" accept=".pdf">
                                </div>
                                <input type="submit" name="upload_tc" value="Confirm">
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
                                    <input type="hidden" name="old_file" value="../uploads/' . $tc_year['file'] . '">
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <div class="payement-month-container">
                                        <input type="submit" name="update_tc" value="Confirm">
                                        <input type="submit" name="delete_tc" value="Delete">
                                    </div>
                                    </div>
                                </td>';
                        }
                    } else {
                        echo '<input type="hidden" name="tc_id" value="' . $tc_year['id'] . '">';
                        if ($tc_year['paied'] == 0) {
                            if ($tc_year['notified'] == 1) {
                                echo '
                                    <td class="payement-month">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified" checked>
                                            <label for="tc_notified">client notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tc" value="Confirm">
                                    </div>
                                    </td>
                                    ';
                            } else {
                                echo '
                                    <td class="payement-month-alert">
                                    <div class="payement-content">
                                        <div class="payement-month-container">
                                            <input type="checkbox" id="tc_notified" name="tc_notified">
                                            <label for="tc_notified">client non notifié</label>
                                        </div>
                                        <div class="payement-month-container">
                                            <input type="file" name="file" id="file" accept=".pdf">
                                        </div>
                                        <input type="submit" name="upload_tc" value="Confirm">
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
                                    <input type="hidden" name="old_file" value="../uploads/' . $tc_year['file'] . '">
                                    <div class="payement-month-container">
                                        <input type="file" name="file" id="file" accept=".pdf">
                                    </div>
                                    <div class="payement-month-container">
                                        <input type="submit" name="update_tc" value="Confirm">
                                        <input type="submit" name="delete_tc" value="Delete">
                                    </div>
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