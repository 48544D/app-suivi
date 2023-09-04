<?php
include '../includes/header-user.php';
?>

<title>Home</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        var type = $($('#type')).val();
        if (type == '1') {
            //change #title to 'Vos sociétés'
            $("#title").text('Vos sociétés');
            //load user-all.php in #content
            $("#content").load('user-all.php');
        } else if (type == '2') {
            //change #title to 'Alert du mois'
            $("#title").text('Alert du mois');
            //load user-alert.php in #content
            $("#content").load('user-alert.php');
        }

        $("#type").on('change', function() {
            var type = $(this).val();

            if (type == '1') {
                //change #title to 'Vos sociétés'
                $("#title").text('Vos sociétés');
                //load user-all.php in #content
                $("#content").load('user-all.php');
            } else if (type == '2') {
                //change #title to 'Alert du mois'
                $("#title").text('Alert du mois');
                //load user-alert.php in #content
                $("#content").load('user-alert.php');
            }
        });
    })
</script>

<main>
    <div class="container">
        <div class="header">
            <h1 id="title">Vos Sociétés</h1>
            <select id="type">
                <option value="1">Vos sociétés</option>
                <option value="2">Alert du mois</option>
            </select>
        </div>
        <div class="content" id="content">

        </div>
</main>