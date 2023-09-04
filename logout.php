<?php
    include './includes/connection.php';
    @session_start();
    //end session if user is logged in
    if (isset($_SESSION['user'])) {
        session_destroy();
    }
    //redirect to index.php
    header('Location: index.php');
?>