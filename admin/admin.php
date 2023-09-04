<?php
include '../includes/header-admin.php';
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<title>Home</title>


<script>
    $(document).ready(function(){
        var type = $($('#type')).val();
        if(type == '1'){
            //change #title to 'Toutes les sociétés'
            $("#title").text('Toutes les sociétés');
            //load admin-all.php in #content
            $("#content").load('admin-all.php');
        } else if(type == '2'){
            //change #title to 'Alert du mois'
            $("#title").text('Alert du mois');
            //load admin-alert.php in #content
            $("#content").load('admin-alert.php');
        }

        $("#type").on('change', function(){
            var type = $(this).val();
            
            if(type == '1'){
                //change #title to 'Toutes les sociétés'
                $("#title").text('Toutes les sociétés');
                //load admin-all.php in #content
                $("#content").load('admin-all.php');
            } else if(type == '2'){
                //change #title to 'Alert du mois'
                $("#title").text('Alert du mois');
                //load admin-alert.php in #content
                $("#content").load('admin-alert.php');
            }
        });
    })
</script>

<main>
    <div class="container">
        <div class="header">
            <h1 id="title">Toutes les sociétés</h1>
            <select id="type">
                <option value="1">Toutes les sociétés</option>
                <option value="2">Alert du mois</option>
            </select>
        </div>
        <div class="content" id="content">
            
        </div>
    </div>
</main>