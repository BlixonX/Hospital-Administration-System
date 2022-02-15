<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap');
    *{font-family: 'Rubik', sans-serif;}
    </style>

    <link rel="stylesheet" href="css/system/nav-desktop.css">
    <link rel="stylesheet" href="css/system/nav-mobile.css" media="(max-width: 630px)">
    <?php
        echo '<link rel="stylesheet" href="css/system/'.$_GET['page'].'/desktop.css">';
        echo '<link rel="stylesheet" href="css/system/'.$_GET['page'].'/mobile.css" media="(max-width: 630px)">';
    ?>
    <title>Dashboard - 
        <?php
        switch ($_GET['page'])
        {
        case "search":
        case "appointments":
        case "add":
        case "remove":
            echo strtoupper($_GET['page']);
            break;
        default:
            echo "WELCOME";
        }
        ?>
    </title>
</head>