<?php
include './includes/connection.php';

if (isset($_GET['id'])) {
    $file_name = $db->query('SELECT file FROM tva_mens WHERE id = ' . $_GET['id'])->fetch()['file'];

    $file = './uploads/' . $file_name;

    if (file_exists($file)) {
        header("Content-disposition: attachment; filename=" . $file_name);
        header("Content-type: application/pdf");
        readfile($file);
        exit;
    }
}